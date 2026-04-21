#include "statsdialog.h"

#include <QVBoxLayout>
#include <QHBoxLayout>
#include <QLabel>
#include <QPushButton>
#include <QFrame>
#include <QMouseEvent>
#include <QCursor>
#include <algorithm>

#include <QtCharts/QChart>
#include <QtCharts/QChartView>
#include <QtCharts/QPieSeries>
#include <QtCharts/QPieSlice>

#if QT_VERSION < QT_VERSION_CHECK(6, 0, 0)
QT_CHARTS_USE_NAMESPACE
#endif

// ── Color palette (green theme) ──────────────────────────────────────────────
static const QStringList PALETTE = {
    "#2D5016", "#4A7C1F", "#6A9E2D", "#8BC34A", "#A8D080",
    "#7CB342", "#558B2F", "#C8DFB0", "#B8D89A", "#D6E8C4"
};
static const QString AUTRES_COLOR = "#9E9E9E";

// ── Constructor ──────────────────────────────────────────────────────────────
StatsDialog::StatsDialog(const QList<Agriculteur> &agriculteurs, StatType type, QWidget *parent)
    : QDialog(parent), m_agriculteurs(agriculteurs), m_type(type)
{
    setModal(true);
    setMinimumSize(960, 700);
    setWindowTitle(m_type == OliviersStats
        ? "Statistiques \u2014 R\u00e9partition des Oliviers"
        : "Statistiques \u2014 R\u00e9partition des Superficies");
    setStyleSheet("QDialog { background-color: #F2F6EE; }");
    buildChart();
}

// ── Data computation ─────────────────────────────────────────────────────────
QList<QPair<QString, double>> StatsDialog::computeData()
{
    QList<QPair<QString, double>> list;
    for (const Agriculteur &ag : m_agriculteurs) {
        double val = (m_type == OliviersStats)
            ? ag.getOliviers().toDouble()
            : ag.getSuperficie().toDouble();
        if (val > 0)
            list.append(qMakePair(ag.getNom(), val));
    }

    std::sort(list.begin(), list.end(),
              [](const QPair<QString,double> &a, const QPair<QString,double> &b){
                  return a.second > b.second;
              });

    if (list.size() > 10) {
        double autres = 0;
        for (int i = 10; i < list.size(); ++i) autres += list[i].second;
        list = list.mid(0, 10);
        if (autres > 0)
            list.append(qMakePair(QString("Autres"), autres));
    }
    return list;
}

// ── Clamp hover card inside dialog bounds ────────────────────────────────────
void StatsDialog::clampHoverCard(QPoint pos)
{
    if (!m_hoverCard) return;
    pos.setX(qMax(8, qMin(pos.x(), width()  - m_hoverCard->width()  - 8)));
    pos.setY(qMax(8, qMin(pos.y(), height() - m_hoverCard->height() - 8)));
    m_hoverCard->move(pos);
}

// ── EventFilter: keep hover card glued to cursor ─────────────────────────────
bool StatsDialog::eventFilter(QObject *obj, QEvent *event)
{
    if (m_hoverCard && m_hoverCard->isVisible()
        && event->type() == QEvent::MouseMove)
    {
        QMouseEvent *me = static_cast<QMouseEvent*>(event);
        QWidget     *w  = qobject_cast<QWidget*>(obj);
        QPoint diagPos  = w ? w->mapTo(this, me->pos())
                            : mapFromGlobal(QCursor::pos());
        clampHoverCard(diagPos + QPoint(18, -m_hoverCard->height() / 2));
        m_hoverCard->raise();
    }
    return QDialog::eventFilter(obj, event);
}

// ── Build the whole chart UI ─────────────────────────────────────────────────
void StatsDialog::buildChart()
{
    QVBoxLayout *mainLayout = new QVBoxLayout(this);
    mainLayout->setSpacing(14);
    mainLayout->setContentsMargins(24, 20, 24, 20);

    // ── Gradient header card ──────────────────────────────────────────────────
    QFrame *headerCard = new QFrame;
    headerCard->setStyleSheet(
        "QFrame {"
        "  background: qlineargradient(x1:0,y1:0,x2:1,y2:0,"
        "              stop:0 #2D5016, stop:1 #558B2F);"
        "  border-radius: 12px;"
        "}"
    );
    QVBoxLayout *headerLayout = new QVBoxLayout(headerCard);
    headerLayout->setContentsMargins(24, 16, 24, 16);
    headerLayout->setSpacing(5);

    const bool isOliv = (m_type == OliviersStats);

    QLabel *titleLbl = new QLabel(
        QString(isOliv ? "\U0001FAD2  " : "\U0001F33F  ") +
        (isOliv ? "Répartition des Oliviers"
                : "Répartition des Superficies")
        );

    titleLbl->setStyleSheet(
        "color:#FFFFFF; font-size:20px; font-weight:bold; background:transparent;");

    QLabel *subLbl = new QLabel;
    subLbl->setStyleSheet(
        "color:rgba(255,255,255,0.75); font-size:12px; background:transparent;");

    headerLayout->addWidget(titleLbl);
    headerLayout->addWidget(subLbl);
    mainLayout->addWidget(headerCard);

    // ── Compute data ──────────────────────────────────────────────────────────
    QList<QPair<QString, double>> data = computeData();
    double total = 0;
    for (const auto &p : data) total += p.second;

    subLbl->setText(isOliv
        ? QString("Distribution par agriculteur   \u00b7   Total : %1 oliviers"
                  "   \u00b7   Survolez une section pour les d\u00e9tails").arg((int)total)
        : QString("Distribution par agriculteur   \u00b7   Total : %1 ha"
                  "   \u00b7   Survolez une section pour les d\u00e9tails")
              .arg(total, 0, 'f', 1));

    // ── Chart card ────────────────────────────────────────────────────────────
    QFrame *chartCard = new QFrame;
    chartCard->setStyleSheet(
        "QFrame {"
        "  background-color: #FFFFFF;"
        "  border: 1px solid #D6E8C4;"
        "  border-radius: 12px;"
        "}"
    );
    QVBoxLayout *chartCardLayout = new QVBoxLayout(chartCard);
    chartCardLayout->setContentsMargins(8, 8, 8, 8);

    // ── Pie series ────────────────────────────────────────────────────────────
    QPieSeries *series = new QPieSeries;
    series->setHoleSize(0.42);
    series->setPieSize(0.76);

    // Find the largest slice index
    int    maxIdx = 0;
    double maxVal = 0;
    for (int i = 0; i < data.size(); ++i)
        if (data[i].second > maxVal) { maxVal = data[i].second; maxIdx = i; }

    for (int i = 0; i < data.size(); ++i) {
        const auto &p  = data[i];
        double pct = (total > 0) ? (p.second / total * 100.0) : 0.0;

        // Two-line tooltip text: name on top, value + % below
        QString tt = isOliv
            ? QString("%1\n%2 oliviers  \u00b7  %3%")
                  .arg(p.first).arg((int)p.second).arg(pct, 0, 'f', 1)
            : QString("%1\n%2 ha  \u00b7  %3%")
                  .arg(p.first).arg(p.second, 0, 'f', 1).arg(pct, 0, 'f', 1);

        QPieSlice *slice = series->append(p.first, p.second);
        slice->setBrush(QColor(p.first == "Autres" ? AUTRES_COLOR : PALETTE[i % PALETTE.size()]));
        slice->setPen(QPen(QColor("#FFFFFF"), 2));
        slice->setLabelVisible(false);

        // Dominant slice stays exploded always
        if (i == maxIdx) {
            slice->setExploded(true);
            slice->setExplodeDistanceFactor(0.07);
        }

        // Hover: show floating card + explode + thicker border
        connect(slice, &QPieSlice::hovered, this,
                [this, slice, i, maxIdx, tt](bool entered) {
            if (entered) {
                m_hoverLabel->setText(tt);
                m_hoverCard->adjustSize();
                QPoint pos = mapFromGlobal(QCursor::pos()) + QPoint(18, -m_hoverCard->height() / 2);
                clampHoverCard(pos);
                m_hoverCard->show();
                m_hoverCard->raise();
                if (i != maxIdx) {
                    slice->setExploded(true);
                    slice->setExplodeDistanceFactor(0.06);
                }
                slice->setPen(QPen(QColor("#FFFFFF"), 3));
            } else {
                m_hoverCard->hide();
                if (i != maxIdx) slice->setExploded(false);
                slice->setPen(QPen(QColor("#FFFFFF"), 2));
            }
        });
    }

    // ── QChart ────────────────────────────────────────────────────────────────
    QChart *chart = new QChart;
    chart->addSeries(series);
    chart->setAnimationOptions(QChart::SeriesAnimations);
    chart->setAnimationDuration(700);
    chart->legend()->setVisible(true);
    chart->legend()->setAlignment(Qt::AlignRight);
    chart->legend()->setFont(QFont("Segoe UI", 10));
    chart->legend()->setColor(QColor("#2D5016"));
    chart->legend()->setMarkerShape(QLegend::MarkerShapeRectangle);
    chart->setBackgroundBrush(QBrush(Qt::white));
    chart->setBackgroundRoundness(0);
    chart->setMargins(QMargins(4, 4, 4, 4));

    QChartView *chartView = new QChartView(chart);
    chartView->setRenderHint(QPainter::Antialiasing);
    chartView->setMinimumHeight(490);
    chartView->setMouseTracking(true);
    chartView->installEventFilter(this);

    chartCardLayout->addWidget(chartView);
    mainLayout->addWidget(chartCard, 1);

    // ── Floating hover card ───────────────────────────────────────────────────
    m_hoverCard = new QFrame(this);
    m_hoverCard->setStyleSheet(
        "QFrame {"
        "  background-color: #1E3A0A;"
        "  border-radius: 10px;"
        "  border: 1px solid rgba(255,255,255,0.15);"
        "}"
    );
    m_hoverCard->setAttribute(Qt::WA_TransparentForMouseEvents);

    QVBoxLayout *hcl = new QVBoxLayout(m_hoverCard);
    hcl->setContentsMargins(14, 10, 14, 10);
    hcl->setSpacing(3);

    m_hoverLabel = new QLabel;
    m_hoverLabel->setStyleSheet(
        "color: #FFFFFF;"
        "font-size: 13px;"
        "font-weight: 600;"
        "background: transparent;"
        "line-height: 1.4;"
    );
    m_hoverLabel->setWordWrap(false);
    hcl->addWidget(m_hoverLabel);
    m_hoverCard->hide();

    // ── Close button ──────────────────────────────────────────────────────────
    QHBoxLayout *btnRow = new QHBoxLayout;
    btnRow->addStretch();
    QPushButton *closeBtn = new QPushButton("  Fermer");
    closeBtn->setFixedHeight(36);
    closeBtn->setStyleSheet(
        "QPushButton {"
        "  background-color: #4A7C1F; color: #FFFFFF; border: none;"
        "  border-radius: 8px; padding: 0px 28px;"
        "  font-size: 13px; font-weight: bold;"
        "}"
        "QPushButton:hover   { background-color: #2D5016; }"
        "QPushButton:pressed { background-color: #1E3A0A; }"
    );
    connect(closeBtn, &QPushButton::clicked, this, &QDialog::accept);
    btnRow->addWidget(closeBtn);
    mainLayout->addLayout(btnRow);
}
