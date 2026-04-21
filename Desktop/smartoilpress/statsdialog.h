#ifndef STATSDIALOG_H
#define STATSDIALOG_H

#include <QDialog>
#include <QList>
#include <QPair>
#include "agriculteur.h"

class QLabel;
class QFrame;
class QEvent;

class StatsDialog : public QDialog
{
    Q_OBJECT
public:
    enum StatType { OliviersStats, SuperficieStats };
    explicit StatsDialog(const QList<Agriculteur> &agriculteurs, StatType type,
                         QWidget *parent = nullptr);

protected:
    bool eventFilter(QObject *obj, QEvent *event) override;

private:
    QList<QPair<QString, double>> computeData();
    void buildChart();
    void clampHoverCard(QPoint pos);

    const QList<Agriculteur> &m_agriculteurs;
    StatType                  m_type;

    QFrame *m_hoverCard  = nullptr;
    QLabel *m_hoverLabel = nullptr;
};

#endif // STATSDIALOG_H
