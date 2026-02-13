#include "mainwindow.h"
#include "ui_mainwindow.h"

#include "agriculteur.h"
#include "culture.h"
#include "historique.h"

#include <QAbstractItemView>
#include <QCoreApplication>
#include <QDateTime>
#include <QDir>
#include <QFile>
#include <QFileDialog>
#include <QFrame>
#include <QFileInfo>
#include <QHeaderView>
#include <QList>
#include <QHBoxLayout>
#include <QImage>
#include <QLabel>
#include <QLineEdit>
#include <QComboBox>
#include <QMap>
#include <QMessageBox>
#include <QPainter>
#include <QPrinter>
#include <QPushButton>
#include <QSizePolicy>
#include <QStackedWidget>
#include <QStatusBar>
#include <QTableWidget>
#include <QTableWidgetItem>
#include <QTextStream>
#include <QUiLoader>
#include <QVBoxLayout>
#include <QStringList>
#include <QGroupBox>
#include <QHeaderView>

namespace {
QPixmap loadLogoOilPixmap()
{
    const QString resourcePath = QStringLiteral(":/assets/resoursers/logooil.png");
    if (QFile::exists(resourcePath)) {
        QPixmap px(resourcePath);
        if (!px.isNull()) {
            return px;
        }
    }

    const QString localPath = QDir::cleanPath(QDir::current().filePath(QStringLiteral("logooil.png")));
    if (QFile::exists(localPath)) {
        QPixmap px(localPath);
        if (!px.isNull()) {
            return px;
        }
    }

    return QPixmap();
}

QPixmap trimWhiteBorders(const QPixmap &pixmap)
{
    if (pixmap.isNull()) {
        return pixmap;
    }

    const QImage image = pixmap.toImage().convertToFormat(QImage::Format_ARGB32);
    const int w = image.width();
    const int h = image.height();
    if (w <= 0 || h <= 0) {
        return pixmap;
    }

    auto isBackground = [&image](int x, int y) {
        const QColor c = image.pixelColor(x, y);
        if (c.alpha() < 20) {
            return true;
        }
        return c.red() > 245 && c.green() > 245 && c.blue() > 245;
    };

    int left = 0;
    while (left < w) {
        bool allBg = true;
        for (int y = 0; y < h; ++y) {
            if (!isBackground(left, y)) {
                allBg = false;
                break;
            }
        }
        if (!allBg) break;
        ++left;
    }

    int right = w - 1;
    while (right >= left) {
        bool allBg = true;
        for (int y = 0; y < h; ++y) {
            if (!isBackground(right, y)) {
                allBg = false;
                break;
            }
        }
        if (!allBg) break;
        --right;
    }

    int top = 0;
    while (top < h) {
        bool allBg = true;
        for (int x = left; x <= right; ++x) {
            if (!isBackground(x, top)) {
                allBg = false;
                break;
            }
        }
        if (!allBg) break;
        ++top;
    }

    int bottom = h - 1;
    while (bottom >= top) {
        bool allBg = true;
        for (int x = left; x <= right; ++x) {
            if (!isBackground(x, bottom)) {
                allBg = false;
                break;
            }
        }
        if (!allBg) break;
        --bottom;
    }

    if (left >= right || top >= bottom) {
        return pixmap;
    }

    return QPixmap::fromImage(image.copy(QRect(QPoint(left, top), QPoint(right, bottom))));
}

void addLogoNextToTitle(QWidget *root, const QString &layoutName, const QString &titleName, const QString &logoName)
{
    if (!root) {
        return;
    }

    auto *layout = root->findChild<QHBoxLayout *>(layoutName);
    auto *title = root->findChild<QLabel *>(titleName);
    if (!layout || !title) {
        return;
    }

    title->setAlignment(Qt::AlignLeft | Qt::AlignVCenter);
    title->setStyleSheet(QStringLiteral("color:#FFFFFF; font-size:17px; font-weight:700; padding:0 6px;"));

    QPixmap logo = trimWhiteBorders(loadLogoOilPixmap());
    if (logo.isNull()) {
        return;
    }

    QLabel *logoLabel = root->findChild<QLabel *>(logoName);
    if (!logoLabel) {
        logoLabel = new QLabel(title->parentWidget());
        logoLabel->setObjectName(logoName);
        logoLabel->setAlignment(Qt::AlignLeft | Qt::AlignVCenter);
        logoLabel->setStyleSheet(QStringLiteral("background: transparent;"));
        logoLabel->setMinimumSize(120, 36);
        logoLabel->setMaximumSize(220, 44);

        const int idx = layout->indexOf(title);
        if (idx >= 0) {
            layout->insertWidget(idx, logoLabel, 0, Qt::AlignVCenter);
        } else {
            layout->insertWidget(0, logoLabel, 0, Qt::AlignVCenter);
        }
    }

    const QSize maxLogoSize(200, 40);
    QPixmap finalLogo = logo;
    if (logo.width() > maxLogoSize.width() || logo.height() > maxLogoSize.height()) {
        finalLogo = logo.scaled(maxLogoSize, Qt::KeepAspectRatio, Qt::SmoothTransformation);
    }
    logoLabel->setPixmap(finalLogo);
}

void applyTopBarLogos(QWidget *root)
{
    addLogoNextToTitle(root, QStringLiteral("topBarLayout"), QStringLiteral("appTitle"), QStringLiteral("topBarLogoMain"));
    addLogoNextToTitle(root, QStringLiteral("topBarStockLayout"), QStringLiteral("stockTitle"), QStringLiteral("topBarLogoStock"));
    addLogoNextToTitle(root, QStringLiteral("topBarCommandeLayout"), QStringLiteral("commandeTitle"), QStringLiteral("topBarLogoCommande"));
    addLogoNextToTitle(root, QStringLiteral("topBarAgriculteurLayout"), QStringLiteral("agriculteurTitle"), QStringLiteral("topBarLogoAgriculteur"));
}
}

MainWindow::MainWindow(QWidget *parent)
    : QMainWindow(parent)
    , ui(new Ui::MainWindow)
{
    ui->setupUi(this);
    applyTopBarLogos(ui->centralwidget);

    ui->navActive->setProperty("navState", "normal");
    ui->navButton_2->setProperty("navState", "normal");
    ui->navButton_3->setProperty("navState", "normal");
    ui->navButton_4->setProperty("navState", "normal");
    ui->navAccent->setProperty("navState", "normal");

    const QList<QPushButton*> navButtons = {
        ui->navActive,
        ui->navButton_2,
        ui->navButton_3,
        ui->navButton_4,
        ui->navAccent
    };

    auto setActiveNav = [navButtons](QPushButton *active) {
        for (QPushButton *btn : navButtons) {
            btn->setProperty("navState", btn == active ? "active" : "normal");
            btn->style()->unpolish(btn);
            btn->style()->polish(btn);
        }
    };

    for (QPushButton *btn : navButtons) {
        connect(btn, &QPushButton::clicked, this, [setActiveNav, btn]() {
            setActiveNav(btn);
        });
    }

    setActiveNav(ui->navActive);

    // connect the Employé nav button to show the employee page
    connect(ui->navActive, &QPushButton::clicked, this, &MainWindow::showEmployeePage);

    setupEmployeeUi();

    setupExtractionUi();
    setupStockUi();
    setupCommandeUi();
    setupAgriculteurUi();

    connect(ui->navButton_2, &QPushButton::clicked, this, &MainWindow::showAgriculteurPage);
}

void MainWindow::setupExtractionUi()
{
    if (ui->table_ext->columnCount() != 4) {
        ui->table_ext->setColumnCount(4);
        ui->table_ext->setHorizontalHeaderLabels(
            QStringList() << tr("ID") << tr("Date") << tr("Type d'Huile") << tr("N° Citerne")
        );
    }
    ui->table_ext->setSelectionBehavior(QAbstractItemView::SelectRows);
    ui->table_ext->setEditTriggers(QAbstractItemView::NoEditTriggers);
    ui->table_ext->setSortingEnabled(true);
    ui->table_ext->horizontalHeader()->setSectionResizeMode(QHeaderView::Stretch);

    connect(ui->btnSearchExt, &QPushButton::clicked, this, [this]() {
        applyExtractionFilter(ui->searchExt->text());
    });
    connect(ui->btnSortExt, &QPushButton::clicked, this, [this]() {
        const int col = ui->sortExt->currentIndex();
        const Qt::SortOrder order = (ui->sortOrderExt->currentIndex() == 0)
            ? Qt::AscendingOrder
            : Qt::DescendingOrder;
        ui->table_ext->sortItems(col, order);
    });
    connect(ui->btn_valider_ext, &QPushButton::clicked, this, &MainWindow::addExtractionRow);
    connect(ui->btn_modifier_ext, &QPushButton::clicked, this, &MainWindow::modifySelectedExtractionRow);
    connect(ui->btn_supprimer_ext, &QPushButton::clicked, this, &MainWindow::deleteSelectedExtractionRow);
    connect(ui->btnExportPdfExt, &QPushButton::clicked, this, &MainWindow::exportExtractionPdf);
    connect(ui->btnStatsExt, &QPushButton::clicked, this, &MainWindow::showExtractionStats);
    connect(ui->table_ext, &QTableWidget::itemSelectionChanged, this, &MainWindow::loadSelectedExtractionRow);

    connect(ui->navButton_4, &QPushButton::clicked, this, &MainWindow::showExtractionPage);
    connect(ui->navButton_3, &QPushButton::clicked, this, &MainWindow::showStockPage);
    connect(ui->navAccent, &QPushButton::clicked, this, &MainWindow::showCommandePage);
}

void MainWindow::setupStockUi()
{
    if (!ui->stockHostLayout) {
        return;
    }

    auto *layout = ui->stockHostLayout;

    QUiLoader loader;
    const QString appDir = QCoreApplication::applicationDirPath();
    const QStringList uiCandidates = {
        QDir::current().filePath(QStringLiteral("stock/mainwindow.ui")),
        appDir + QStringLiteral("/stock/mainwindow.ui"),
        appDir + QStringLiteral("/../stock/mainwindow.ui"),
        appDir + QStringLiteral("/../../stock/mainwindow.ui"),
        appDir + QStringLiteral("/../../../stock/mainwindow.ui")
    };

    QWidget *loaded = nullptr;
    QString loadedPath;
    auto hideStockChrome = [](QWidget *root) {
        if (!root) {
            return;
        }
        const auto widgets = root->findChildren<QWidget *>();
        for (QWidget *w : widgets) {
            const QString name = w->objectName().toLower();
            if (name.contains("sidebar") || name.contains("topbar")
                || name.contains("menubar") || name.contains("statusbar")) {
                w->setVisible(false);
            }
        }
    };

    for (const QString &path : uiCandidates) {
        const QString cleanPath = QDir::cleanPath(path);
        QFile file(path);
        if (file.open(QFile::ReadOnly)) {
            loaded = loader.load(&file, ui->stockHost);
            file.close();
            if (loaded) {
                loadedPath = cleanPath;
                break;
            }
        }
    }

    if (auto *mw = qobject_cast<QMainWindow *>(loaded)) {
        QWidget *central = mw->takeCentralWidget();
        mw->deleteLater();
        loaded = central;
    }

    if (loaded) {
        loaded->setParent(ui->stockHost);
        layout->addWidget(loaded);
        stockRoot = loaded;
        hideStockChrome(stockRoot);
        statusBar()->showMessage(tr("Module Stock chargé: %1").arg(loadedPath), 3000);
    } else {
        QStringList tried;
        for (const QString &path : uiCandidates) {
            const QString cleanPath = QDir::cleanPath(path);
            tried << QString("%1 [%2]").arg(cleanPath, QFileInfo::exists(cleanPath) ? "OK" : "introuvable");
        }
        auto *label = new QLabel(tr("Impossible de charger l'UI Stock.\n%1").arg(tried.join("\n")));
        label->setWordWrap(true);
        label->setStyleSheet("color:#B71C1C; font-weight:600;");
        layout->addWidget(label);
        statusBar()->showMessage(tr("UI Stock introuvable."), 5000);
    }
}

void MainWindow::applyExtractionFilter(const QString &text)
{
    const QString needle = text.trimmed();
    for (int row = 0; row < ui->table_ext->rowCount(); ++row) {
        bool match = needle.isEmpty();
        for (int col = 0; col < ui->table_ext->columnCount() && !match; ++col) {
            QTableWidgetItem *item = ui->table_ext->item(row, col);
            if (item && item->text().contains(needle, Qt::CaseInsensitive)) {
                match = true;
            }
        }
        ui->table_ext->setRowHidden(row, !match);
    }
}

void MainWindow::addExtractionRow()
{
    const QString id = ui->id_ext->text().trimmed();
    if (id.isEmpty()) {
        QMessageBox::warning(this, tr("Validation"), tr("Veuillez saisir l'ID."));
        return;
    }

    const QString date = ui->date_ext->date().toString("dd/MM/yyyy");
    const QString type = ui->combo_type_ext->currentText();
    const QString citerneText = ui->citerne_ext->text().trimmed();
    bool ok = false;
    const int citerneVal = citerneText.toInt(&ok);
    if (!ok || citerneVal < 1 || citerneVal > 50) {
        QMessageBox::warning(this, tr("Validation"), tr("Veuillez saisir un numéro de citerne entre 1 et 50."));
        return;
    }
    const QString citerne = QString::number(citerneVal);

    const int row = ui->table_ext->rowCount();
    ui->table_ext->insertRow(row);
    auto *itemId = new QTableWidgetItem(id);
    auto *itemDate = new QTableWidgetItem(date);
    auto *itemType = new QTableWidgetItem(type);
    auto *itemCiterne = new QTableWidgetItem(citerne);
    itemId->setForeground(Qt::black);
    itemDate->setForeground(Qt::black);
    itemType->setForeground(Qt::black);
    itemCiterne->setForeground(Qt::black);
    ui->table_ext->setItem(row, 0, itemId);
    ui->table_ext->setItem(row, 1, itemDate);
    ui->table_ext->setItem(row, 2, itemType);
    ui->table_ext->setItem(row, 3, itemCiterne);
    ui->table_ext->setRowHidden(row, false);

    ui->id_ext->clear();
    ui->date_ext->setDate(QDate::currentDate());
    ui->combo_type_ext->setCurrentIndex(0);
    ui->citerne_ext->clear();

    ui->searchExt->clear();
    applyExtractionFilter(QString());
    ui->table_ext->scrollToItem(ui->table_ext->item(row, 0));
    statusBar()->showMessage(tr("Ligne ajoutée."), 2000);
}


void MainWindow::loadSelectedExtractionRow()
{
    const int row = ui->table_ext->currentRow();
    if (row < 0) {
        return;
    }

    const auto *itemId = ui->table_ext->item(row, 0);
    const auto *itemDate = ui->table_ext->item(row, 1);
    const auto *itemType = ui->table_ext->item(row, 2);
    const auto *itemCiterne = ui->table_ext->item(row, 3);

    ui->id_ext->setText(itemId ? itemId->text() : QString());

    const QDate parsedDate = QDate::fromString(itemDate ? itemDate->text() : QString(), "dd/MM/yyyy");
    if (parsedDate.isValid()) {
        ui->date_ext->setDate(parsedDate);
    }

    const QString type = itemType ? itemType->text() : QString();
    const int typeIndex = ui->combo_type_ext->findText(type);
    if (typeIndex >= 0) {
        ui->combo_type_ext->setCurrentIndex(typeIndex);
    }

    ui->citerne_ext->setText(itemCiterne ? itemCiterne->text() : QString());
}

void MainWindow::modifySelectedExtractionRow()
{
    const int row = ui->table_ext->currentRow();
    if (row < 0) {
        QMessageBox::warning(this, tr("Modification"), tr("Veuillez sélectionner une ligne."));
        return;
    }

    const QString id = ui->id_ext->text().trimmed();
    if (id.isEmpty()) {
        QMessageBox::warning(this, tr("Validation"), tr("Veuillez saisir l'ID."));
        return;
    }

    const QString date = ui->date_ext->date().toString("dd/MM/yyyy");
    const QString type = ui->combo_type_ext->currentText();
    const QString citerneText = ui->citerne_ext->text().trimmed();
    bool ok = false;
    const int citerneVal = citerneText.toInt(&ok);
    if (!ok || citerneVal < 1 || citerneVal > 50) {
        QMessageBox::warning(this, tr("Validation"), tr("Veuillez saisir un numéro de citerne entre 1 et 50."));
        return;
    }

    ui->table_ext->setItem(row, 0, new QTableWidgetItem(id));
    ui->table_ext->setItem(row, 1, new QTableWidgetItem(date));
    ui->table_ext->setItem(row, 2, new QTableWidgetItem(type));
    ui->table_ext->setItem(row, 3, new QTableWidgetItem(QString::number(citerneVal)));
    statusBar()->showMessage(tr("Ligne modifiée."), 2000);
}

void MainWindow::deleteSelectedExtractionRow()
{
    const int row = ui->table_ext->currentRow();
    if (row < 0) {
        QMessageBox::warning(this, tr("Suppression"), tr("Veuillez sélectionner une ligne."));
        return;
    }

    ui->table_ext->removeRow(row);
    statusBar()->showMessage(tr("Ligne supprimée."), 2000);
}

void MainWindow::setupCommandeUi()
{
    if (!ui->commandeHostLayout) {
        return;
    }

    auto *layout = ui->commandeHostLayout;

    QUiLoader loader;
    const QString appDir = QCoreApplication::applicationDirPath();
    const QStringList uiCandidates = {
        QDir::current().filePath(QStringLiteral("commande/mainwindow.ui")),
        appDir + QStringLiteral("/commande/mainwindow.ui"),
        appDir + QStringLiteral("/../commande/mainwindow.ui"),
        appDir + QStringLiteral("/../../commande/mainwindow.ui"),
        appDir + QStringLiteral("/../../../commande/mainwindow.ui")
    };

    QWidget *loaded = nullptr;
    QString loadedPath;
    auto hideCommandeChrome = [](QWidget *root) {
        if (!root) {
            return;
        }
        const auto widgets = root->findChildren<QWidget *>();
        for (QWidget *w : widgets) {
            const QString name = w->objectName().toLower();
            if (name.contains("sidebar") || name.contains("topbar")) {
                w->setVisible(false);
            }
        }
    };

    for (const QString &path : uiCandidates) {
        const QString cleanPath = QDir::cleanPath(path);
        QFile file(path);
        if (file.open(QFile::ReadOnly)) {
            loaded = loader.load(&file, ui->commandeHost);
            file.close();
            if (loaded) {
                loadedPath = cleanPath;
                break;
            }
        }
    }

    if (auto *mw = qobject_cast<QMainWindow *>(loaded)) {
        QWidget *central = mw->takeCentralWidget();
        mw->deleteLater();
        loaded = central;
    }

    if (loaded) {
        loaded->setParent(ui->commandeHost);
        layout->addWidget(loaded);
        commandeRoot = loaded;
        hideCommandeChrome(commandeRoot);
        commandeUi_.root = commandeRoot;
        applyCommandeTheme();
        if (resolveCommandeWidgets()) {
            setupCommandeTable();
            setupCommandeConnections();
        }
        statusBar()->showMessage(tr("Module Commande chargé: %1").arg(loadedPath), 3000);
    } else {
        QStringList tried;
        for (const QString &path : uiCandidates) {
            const QString cleanPath = QDir::cleanPath(path);
            tried << QString("%1 [%2]").arg(cleanPath, QFileInfo::exists(cleanPath) ? "OK" : "introuvable");
        }
        auto *label = new QLabel(tr("Impossible de charger l'UI Commande.\n%1").arg(tried.join("\n")));
        label->setWordWrap(true);
        label->setStyleSheet("color:#B71C1C; font-weight:600;");
        layout->addWidget(label);
        statusBar()->showMessage(tr("UI Commande introuvable."), 5000);
    }
}

bool MainWindow::resolveCommandeWidgets()
{
    if (!commandeRoot) {
        return false;
    }

    auto findLineEdit = [this](const char *name) {
        return commandeRoot->findChild<QLineEdit *>(name);
    };
    auto findCombo = [this](const char *name) {
        return commandeRoot->findChild<QComboBox *>(name);
    };
    auto findButton = [this](const char *name) {
        return commandeRoot->findChild<QPushButton *>(name);
    };
    auto findTable = [this](const char *name) {
        return commandeRoot->findChild<QTableWidget *>(name);
    };

    commandeUi_.searchLineEdit = findLineEdit("searchLineEdit");
    commandeUi_.filterStatusCombo = findCombo("filterStatusCombo");
    commandeUi_.filterButton = findButton("filterButton");
    commandeUi_.resetFilterButton = findButton("resetFilterButton");
    commandeUi_.sortColumnCombo = findCombo("sortColumnCombo");
    commandeUi_.sortOrderCombo = findCombo("sortOrderCombo");
    commandeUi_.sortButton = findButton("sortButton");
    commandeUi_.exportButton = findButton("exportButton");
    commandeUi_.tableWidget = findTable("tableWidget");
    commandeUi_.idLineEdit = findLineEdit("idLineEdit");
    commandeUi_.farmerLineEdit = findLineEdit("farmerLineEdit");
    commandeUi_.dateLineEdit = findLineEdit("dateLineEdit");
    commandeUi_.qtyLineEdit = findLineEdit("qtyLineEdit");
    commandeUi_.priceLineEdit = findLineEdit("priceLineEdit");
    commandeUi_.totalLineEdit = findLineEdit("totalLineEdit");
    commandeUi_.livreurLineEdit = findLineEdit("livreurLineEdit");
    commandeUi_.emailLineEdit = findLineEdit("emailLineEdit");
    commandeUi_.emailLivreurLineEdit = findLineEdit("emailLivreurLineEdit");
    commandeUi_.statusCombo = findCombo("statusCombo");
    commandeUi_.addButton = findButton("addButton");
    commandeUi_.updateButton = findButton("updateButton");
    commandeUi_.deleteButton = findButton("deleteButton");
    commandeUi_.clearButton = findButton("clearButton");
    commandeUi_.addQuickButton = findButton("addQuickButton");

    const bool ok = commandeUi_.searchLineEdit && commandeUi_.filterStatusCombo
        && commandeUi_.filterButton && commandeUi_.resetFilterButton
        && commandeUi_.sortColumnCombo && commandeUi_.sortOrderCombo
        && commandeUi_.sortButton && commandeUi_.exportButton
        && commandeUi_.tableWidget && commandeUi_.idLineEdit
        && commandeUi_.farmerLineEdit && commandeUi_.dateLineEdit
        && commandeUi_.qtyLineEdit && commandeUi_.priceLineEdit
        && commandeUi_.totalLineEdit && commandeUi_.livreurLineEdit
        && commandeUi_.emailLineEdit && commandeUi_.emailLivreurLineEdit
        && commandeUi_.statusCombo
        && commandeUi_.addButton && commandeUi_.updateButton
        && commandeUi_.deleteButton && commandeUi_.clearButton
        && commandeUi_.addQuickButton;

    if (!ok) {
        statusBar()->showMessage(tr("Certains widgets Commande sont introuvables."), 5000);
    }
    return ok;
}

void MainWindow::applyCommandeTheme()
{
    if (!commandeRoot) {
        return;
    }
    const QString style =
        "QWidget {"
        "  font-family: 'Segoe UI';"
        "  font-size: 12px;"
        "  background: #F4EEE4;"
        "  color: #1F2937;"
        "}"
        "QLineEdit, QComboBox, QDateEdit, QSpinBox {"
        "  background: #FFFBF5;"
        "  border: 2px solid #2B5978;"
        "  border-radius: 6px;"
        "  padding: 6px 10px;"
        "  color: #333;"
        "}"
        "QPushButton {"
        "  background: #6B8E23;"
        "  color: #FFFFFF;"
        "  border: none;"
        "  border-radius: 6px;"
        "  padding: 8px 14px;"
        "  font-weight: 600;"
        "}"
        "QPushButton#exportButton, QPushButton#filterButton {"
        "  background: #F2B705;"
        "  color: #2B2B2B;"
        "}"
        "QPushButton#deleteButton {"
        "  background: #C62828;"
        "  color: #FFFFFF;"
        "}"
        "QPushButton#deleteButton:hover {"
        "  background: #B71C1C;"
        "}"
        "QPushButton#exportButton:hover, QPushButton#filterButton:hover {"
        "  background: #E0A800;"
        "  color: #2B2B2B;"
        "}"
        "QPushButton:hover {"
        "  background: #5A7A1E;"
        "  color: #FFFFFF;"
        "}"
        "QTableWidget {"
        "  background: #FFFFFF;"
        "  border: 1px solid #E3D8C7;"
        "  border-radius: 10px;"
        "  gridline-color: #E3D8C7;"
        "  selection-background-color: #FFE9B5;"
        "  selection-color: #000000;"
        "}"
        "QHeaderView::section {"
        "  background: #2B5978;"
        "  color: #FFFFFF;"
        "  padding: 8px;"
        "  border: none;"
        "  font-weight: 600;"
        "}";
    commandeRoot->setStyleSheet(style);
}

void MainWindow::setupCommandeTable()
{
    if (!commandeUi_.tableWidget) {
        return;
    }
    commandeUi_.tableWidget->setColumnCount(10);
    commandeUi_.tableWidget->setHorizontalHeaderLabels({
        tr("ID Commande"),
        tr("ID Agriculteur"),
        tr("Date de commande"),
        tr("Quantite commandee"),
        tr("Prix unitaire"),
        tr("Montant total"),
        tr("ID Livreur"),
        tr("Email"),
        tr("Email Livreur"),
        tr("Statut")
    });
    commandeUi_.tableWidget->horizontalHeader()->setSectionResizeMode(QHeaderView::Stretch);
    commandeUi_.tableWidget->setAlternatingRowColors(true);
    commandeUi_.tableWidget->setSelectionBehavior(QAbstractItemView::SelectRows);
    commandeUi_.tableWidget->setEditTriggers(QAbstractItemView::NoEditTriggers);
}

void MainWindow::setupCommandeConnections()
{
    connect(commandeUi_.addButton, &QPushButton::clicked, this, &MainWindow::commandeAdd);
    connect(commandeUi_.updateButton, &QPushButton::clicked, this, &MainWindow::commandeUpdate);
    connect(commandeUi_.deleteButton, &QPushButton::clicked, this, &MainWindow::commandeDelete);
    connect(commandeUi_.clearButton, &QPushButton::clicked, this, &MainWindow::commandeClear);
    connect(commandeUi_.filterButton, &QPushButton::clicked, this, &MainWindow::commandeFilter);
    connect(commandeUi_.resetFilterButton, &QPushButton::clicked, this, &MainWindow::commandeResetFilter);
    connect(commandeUi_.sortButton, &QPushButton::clicked, this, &MainWindow::commandeSort);
    connect(commandeUi_.exportButton, &QPushButton::clicked, this, &MainWindow::commandeExport);
    connect(commandeUi_.addQuickButton, &QPushButton::clicked, this, &MainWindow::commandeQuickAdd);
    connect(commandeUi_.tableWidget, &QTableWidget::itemSelectionChanged, this, &MainWindow::commandeTableSelectionChanged);
}

bool MainWindow::commandeReadForm(QString &id, QString &farmerId, QString &date,
                                  double &qty, double &price, double &total,
                                  QString &livreurId, QString &emailClient,
                                  QString &emailLivreur, QString &status)
{
    id = commandeUi_.idLineEdit->text().trimmed();
    farmerId = commandeUi_.farmerLineEdit->text().trimmed();
    date = commandeUi_.dateLineEdit->text().trimmed();
    const QString qtyStr = commandeUi_.qtyLineEdit->text().trimmed();
    const QString priceStr = commandeUi_.priceLineEdit->text().trimmed();
    const QString totalStr = commandeUi_.totalLineEdit->text().trimmed();
    livreurId = commandeUi_.livreurLineEdit->text().trimmed();
    emailClient = commandeUi_.emailLineEdit->text().trimmed();
    emailLivreur = commandeUi_.emailLivreurLineEdit->text().trimmed();
    status = commandeUi_.statusCombo->currentText().trimmed();

    if (id.isEmpty() || farmerId.isEmpty() || date.isEmpty() || qtyStr.isEmpty() || priceStr.isEmpty()) {
        QMessageBox::warning(this, tr("Champs manquants"), tr("Veuillez remplir ID, agriculteur, date, quantite et prix."));
        return false;
    }

    bool okQty = false;
    bool okPrice = false;
    qty = qtyStr.toDouble(&okQty);
    price = priceStr.toDouble(&okPrice);
    if (!okQty || !okPrice) {
        QMessageBox::warning(this, tr("Valeurs invalides"), tr("Quantite et prix doivent etre numeriques."));
        return false;
    }

    if (totalStr.isEmpty()) {
        total = qty * price;
    } else {
        bool okTotal = false;
        total = totalStr.toDouble(&okTotal);
        if (!okTotal) {
            QMessageBox::warning(this, tr("Valeur invalide"), tr("Montant total doit etre numerique."));
            return false;
        }
    }

    return true;
}

void MainWindow::commandeSetRow(int row, const QString &id, const QString &farmerId, const QString &date,
                                double qty, double price, double total,
                                const QString &livreurId, const QString &emailClient,
                                const QString &emailLivreur, const QString &status)
{
    commandeUi_.tableWidget->setItem(row, 0, new QTableWidgetItem(id));
    commandeUi_.tableWidget->setItem(row, 1, new QTableWidgetItem(farmerId));
    commandeUi_.tableWidget->setItem(row, 2, new QTableWidgetItem(date));
    commandeUi_.tableWidget->setItem(row, 3, new QTableWidgetItem(commandeFormatNumber(qty)));
    commandeUi_.tableWidget->setItem(row, 4, new QTableWidgetItem(commandeFormatNumber(price)));
    commandeUi_.tableWidget->setItem(row, 5, new QTableWidgetItem(commandeFormatNumber(total)));
    commandeUi_.tableWidget->setItem(row, 6, new QTableWidgetItem(livreurId));
    commandeUi_.tableWidget->setItem(row, 7, new QTableWidgetItem(emailClient));
    commandeUi_.tableWidget->setItem(row, 8, new QTableWidgetItem(emailLivreur));
    commandeUi_.tableWidget->setItem(row, 9, new QTableWidgetItem(status));
}

int MainWindow::commandeSelectedRow() const
{
    auto rows = commandeUi_.tableWidget->selectionModel()->selectedRows();
    if (rows.isEmpty()) {
        return -1;
    }
    return rows.first().row();
}

void MainWindow::commandeFillFormFromRow(int row)
{
    commandeUi_.idLineEdit->setText(commandeUi_.tableWidget->item(row, 0)->text());
    commandeUi_.farmerLineEdit->setText(commandeUi_.tableWidget->item(row, 1)->text());
    commandeUi_.dateLineEdit->setText(commandeUi_.tableWidget->item(row, 2)->text());
    commandeUi_.qtyLineEdit->setText(commandeUi_.tableWidget->item(row, 3)->text());
    commandeUi_.priceLineEdit->setText(commandeUi_.tableWidget->item(row, 4)->text());
    commandeUi_.totalLineEdit->setText(commandeUi_.tableWidget->item(row, 5)->text());
    commandeUi_.livreurLineEdit->setText(commandeUi_.tableWidget->item(row, 6)->text());
    commandeUi_.emailLineEdit->setText(commandeUi_.tableWidget->item(row, 7)->text());
    commandeUi_.emailLivreurLineEdit->setText(commandeUi_.tableWidget->item(row, 8)->text());

    const QString status = commandeUi_.tableWidget->item(row, 9)->text();
    const int index = commandeUi_.statusCombo->findText(status);
    if (index >= 0) {
        commandeUi_.statusCombo->setCurrentIndex(index);
    }
}

QString MainWindow::commandeFormatNumber(double value) const
{
    return QString::number(value, 'f', 2);
}

QString MainWindow::commandeCsvEscape(const QString &text) const
{
    QString out = text;
    if (out.contains('"')) {
        out.replace("\"", "\"\"");
    }
    if (out.contains(',') || out.contains('\n') || out.contains('"')) {
        out = "\"" + out + "\"";
    }
    return out;
}

void MainWindow::commandeFilterRows(const QString &query, const QString &status)
{
    for (int row = 0; row < commandeUi_.tableWidget->rowCount(); ++row) {
        bool matchQuery = query.isEmpty();
        if (!matchQuery) {
            for (int col = 0; col < commandeUi_.tableWidget->columnCount(); ++col) {
                auto *item = commandeUi_.tableWidget->item(row, col);
                if (item && item->text().contains(query, Qt::CaseInsensitive)) {
                    matchQuery = true;
                    break;
                }
            }
        }

        bool matchStatus = (status == tr("Tous statuts"));
        if (!matchStatus) {
            auto *statusItem = commandeUi_.tableWidget->item(row, 9);
            if (statusItem && statusItem->text().compare(status, Qt::CaseInsensitive) == 0) {
                matchStatus = true;
            }
        }

        commandeUi_.tableWidget->setRowHidden(row, !(matchQuery && matchStatus));
    }
}

void MainWindow::commandeAdd()
{
    QString id, farmerId, date, livreurId, emailClient, emailLivreur, status;
    double qty = 0.0, price = 0.0, total = 0.0;
    if (!commandeReadForm(id, farmerId, date, qty, price, total, livreurId, emailClient, emailLivreur, status)) {
        return;
    }

    const int row = commandeUi_.tableWidget->rowCount();
    commandeUi_.tableWidget->insertRow(row);
    commandeSetRow(row, id, farmerId, date, qty, price, total, livreurId, emailClient, emailLivreur, status);
    commandeClear();
}

void MainWindow::commandeUpdate()
{
    const int row = commandeSelectedRow();
    if (row < 0) {
        QMessageBox::warning(this, tr("Selection requise"), tr("Selectionnez une ligne a modifier."));
        return;
    }

    QString id, farmerId, date, livreurId, emailClient, emailLivreur, status;
    double qty = 0.0, price = 0.0, total = 0.0;
    if (!commandeReadForm(id, farmerId, date, qty, price, total, livreurId, emailClient, emailLivreur, status)) {
        return;
    }

    commandeSetRow(row, id, farmerId, date, qty, price, total, livreurId, emailClient, emailLivreur, status);
}

void MainWindow::commandeDelete()
{
    const int row = commandeSelectedRow();
    if (row < 0) {
        QMessageBox::warning(this, tr("Selection requise"), tr("Selectionnez une ligne a supprimer."));
        return;
    }

    commandeUi_.tableWidget->removeRow(row);
    commandeClear();
}

void MainWindow::commandeClear()
{
    commandeUi_.idLineEdit->clear();
    commandeUi_.farmerLineEdit->clear();
    commandeUi_.dateLineEdit->clear();
    commandeUi_.qtyLineEdit->clear();
    commandeUi_.priceLineEdit->clear();
    commandeUi_.totalLineEdit->clear();
    commandeUi_.livreurLineEdit->clear();
    commandeUi_.emailLineEdit->clear();
    commandeUi_.emailLivreurLineEdit->clear();
    commandeUi_.statusCombo->setCurrentIndex(0);
    commandeUi_.tableWidget->clearSelection();
    commandeUi_.idLineEdit->setFocus();
}

void MainWindow::commandeFilter()
{
    commandeFilterRows(commandeUi_.searchLineEdit->text().trimmed(),
                       commandeUi_.filterStatusCombo->currentText().trimmed());
}

void MainWindow::commandeResetFilter()
{
    commandeUi_.searchLineEdit->clear();
    commandeUi_.filterStatusCombo->setCurrentIndex(0);
    commandeFilterRows(QString(), tr("Tous statuts"));
}

void MainWindow::commandeSort()
{
    const int col = commandeUi_.sortColumnCombo->currentIndex();
    const Qt::SortOrder order = (commandeUi_.sortOrderCombo->currentIndex() == 0)
        ? Qt::AscendingOrder
        : Qt::DescendingOrder;
    commandeUi_.tableWidget->sortItems(col, order);
}

void MainWindow::commandeExport()
{
    const QString filePath = QFileDialog::getSaveFileName(this, tr("Ecrire CSV"), "commandes.csv", tr("CSV Files (*.csv)"));
    if (filePath.isEmpty()) {
        return;
    }

    QFile file(filePath);
    if (!file.open(QIODevice::WriteOnly | QIODevice::Text)) {
        QMessageBox::warning(this, tr("Erreur"), tr("Impossible d'ouvrir le fichier pour ecriture."));
        return;
    }

    QTextStream out(&file);
    QStringList headers;
    for (int col = 0; col < commandeUi_.tableWidget->columnCount(); ++col) {
        headers << commandeCsvEscape(commandeUi_.tableWidget->horizontalHeaderItem(col)->text());
    }
    out << headers.join(',') << "\n";

    for (int row = 0; row < commandeUi_.tableWidget->rowCount(); ++row) {
        if (commandeUi_.tableWidget->isRowHidden(row)) {
            continue;
        }
        QStringList rowData;
        for (int col = 0; col < commandeUi_.tableWidget->columnCount(); ++col) {
            auto *item = commandeUi_.tableWidget->item(row, col);
            rowData << commandeCsvEscape(item ? item->text() : "");
        }
        out << rowData.join(',') << "\n";
    }

    file.close();
    QMessageBox::information(this, tr("Export termine"), tr("Le fichier CSV a ete ecrit avec succes."));
}

void MainWindow::commandeTableSelectionChanged()
{
    const int row = commandeSelectedRow();
    if (row >= 0) {
        commandeFillFormFromRow(row);
    }
}

void MainWindow::commandeQuickAdd()
{
    commandeClear();
}

void MainWindow::showExtractionPage()
{
    if (ui->stackedModules) {
        ui->stackedModules->setCurrentIndex(0);
    }
    ui->appTitle->setText(tr("EXTRACTION"));
}

void MainWindow::showEmployeePage()
{
    // If already loaded into the stacked widget, just show it
    if (employeeRoot && ui->stackedModules) {
        ui->stackedModules->setCurrentWidget(employeeRoot);
        ui->appTitle->setText(tr("EMPLOY\302\251"));
        return;
    }

    // Load the employee UI and embed it into the main stacked widget so it replaces
    // the central area (no duplicate sidebars).
    QUiLoader loader;
    const QString appDir = QCoreApplication::applicationDirPath();
    const QStringList uiCandidates = {
        QDir::current().filePath(QStringLiteral("employee/mainwindow.ui")),
        appDir + QStringLiteral("/employee/mainwindow.ui"),
        appDir + QStringLiteral("/../employee/mainwindow.ui"),
        appDir + QStringLiteral("/../../employee/mainwindow.ui")
    };

    QWidget *loaded = nullptr;
    QString loadedPath;
    for (const QString &path : uiCandidates) {
        QFile file(path);
        if (file.open(QFile::ReadOnly)) {
            loaded = loader.load(&file, nullptr);
            file.close();
            if (loaded) {
                loadedPath = QDir::cleanPath(path);
                break;
            }
        }
    }

    if (!loaded) {
        // fallback to existing embedded page if any
        if (ui->stackedModules) {
            if (employeeRoot) ui->stackedModules->setCurrentWidget(employeeRoot);
            else ui->stackedModules->setCurrentIndex(0);
        }
        ui->appTitle->setText(tr("EMPLOY\302\251"));
        statusBar()->showMessage(tr("UI Employé introuvable."), 3000);
        return;
    }

    // If the loaded UI is a QMainWindow, take its central widget and discard the wrapping window
    QWidget *host = nullptr;
    if (auto *mw = qobject_cast<QMainWindow *>(loaded)) {
        host = mw->takeCentralWidget();
        mw->deleteLater();
    } else {
        host = loaded;
    }

    if (!host) {
        statusBar()->showMessage(tr("UI Employé chargée mais aucun contenu trouvé."), 3000);
        return;
    }

    // Remove conflicting chrome inside the host (keep top bar visible).
    for (QWidget *w : host->findChildren<QWidget *>()) {
        const QString name = w->objectName().toLower();
        if (name.contains("sidebar") || name.contains("menubar") || name.contains("statusbar")) {
            w->setVisible(false);
            if (name.contains("sidebar")) {
                w->setMinimumWidth(0);
                w->setMaximumWidth(0);
                w->setFixedWidth(0);
            }
        }
    }

    // Add to stackedModules and display
    if (ui->stackedModules) {
        host->setParent(ui->stackedModules);
        ui->stackedModules->addWidget(host);
        applyTopBarLogos(host);
        ui->stackedModules->setCurrentWidget(host);
        employeeRoot = host;
        ui->appTitle->setText(tr("EMPLOY\302\251"));
        statusBar()->showMessage(tr("Module Employé chargé: %1").arg(loadedPath), 3000);
    } else {
        // as a last resort, show as standalone
        host->setAttribute(Qt::WA_DeleteOnClose);
        host->setWindowTitle(tr("EMPLOY\302\251"));
        host->show();
        statusBar()->showMessage(tr("Module Employé ouvert: %1").arg(loadedPath), 3000);
    }
}

void MainWindow::setupEmployeeUi()
{
    if (!ui->stackedModules) {
        return;
    }

    QUiLoader loader;
    const QString appDir = QCoreApplication::applicationDirPath();
    const QStringList uiCandidates = {
        QDir::current().filePath(QStringLiteral("employee/mainwindow.ui")),
        appDir + QStringLiteral("/employee/mainwindow.ui"),
        appDir + QStringLiteral("/../employee/mainwindow.ui"),
        appDir + QStringLiteral("/../../employee/mainwindow.ui")
    };

    QWidget *loaded = nullptr;
    QString loadedPath;
    for (const QString &path : uiCandidates) {
        QFile file(path);
        if (file.open(QFile::ReadOnly)) {
            loaded = loader.load(&file, ui->stackedModules);
            file.close();
            if (loaded) {
                loadedPath = QDir::cleanPath(path);
                break;
            }
        }
    }

    if (auto *mw = qobject_cast<QMainWindow *>(loaded)) {
        QWidget *central = mw->takeCentralWidget();
        mw->deleteLater();
        loaded = central;
    }

    if (loaded) {

        // hide any chrome that may conflict (sidebar, menubar, statusbar). Keep top bar visible.
        const auto widgets = loaded->findChildren<QWidget *>();
        for (QWidget *w : widgets) {
            const QString name = w->objectName().toLower();
            if (name.contains("sidebar") || name.contains("menubar") || name.contains("statusbar")) {
                w->setVisible(false);
            }
        }

        // Force-hide any nested sidebar widgets and collapse their width so they don't appear
        for (QWidget *s : loaded->findChildren<QWidget *>()) {
            const QString name = s->objectName().toLower();
            if (name.contains("sidebar")) {
                s->setVisible(false);
                s->setMinimumWidth(0);
                s->setMaximumWidth(0);
                s->setFixedWidth(0);
            }
        }

        // Ensure employee action buttons have visible labels and contrast
        auto fixButton = [loaded](const char *objName, const QString &text) {
            if (!loaded) return;
            if (auto *btn = loaded->findChild<QPushButton *>(objName)) {
                if (btn->text().trimmed().isEmpty()) {
                    btn->setText(text);
                }
                // ensure readable text color on colored backgrounds
                btn->setStyleSheet(btn->styleSheet() + "\ncolor: #FFFFFF;");
            }
        };

        fixButton("btn_valider_emp", tr("Ajouter"));
        fixButton("btn_modifier_emp", tr("Modifier"));
        fixButton("btn_supprimer_emp", tr("Supprimer"));

        // add as a new page and keep reference
        ui->stackedModules->addWidget(loaded);
        applyTopBarLogos(loaded);
        employeeRoot = loaded;
        statusBar()->showMessage(tr("Module Employé chargé: %1").arg(loadedPath), 3000);
    } else {
        statusBar()->showMessage(tr("UI Employé introuvable."), 5000);
    }
}

void MainWindow::showStockPage()
{
    if (ui->stackedModules) {
        ui->stackedModules->setCurrentIndex(1);
    }
}

void MainWindow::showCommandePage()
{
    if (ui->stackedModules) {
        ui->stackedModules->setCurrentIndex(2);
    }
    ui->appTitle->setText(tr("COMMANDE"));
}

void MainWindow::showAgriculteurPage()
{
    if (agriculteurPage_ && ui->centralwidget) {
        QStackedWidget *stack = ui->centralwidget->findChild<QStackedWidget *>(QStringLiteral("stackedModules"));
        if (stack) {
            stack->setCurrentWidget(agriculteurPage_);
        }
    }
}

bool MainWindow::resolveAgriculteurWidgets()
{
    if (!agriculteurRoot) {
        return false;
    }
    agriculteurUi_.idEdit = agriculteurRoot->findChild<QLineEdit *>("idEdit");
    agriculteurUi_.cinEdit = agriculteurRoot->findChild<QLineEdit *>("cinEdit");
    agriculteurUi_.nomEdit = agriculteurRoot->findChild<QLineEdit *>("nomEdit");
    agriculteurUi_.adresseEdit = agriculteurRoot->findChild<QLineEdit *>("adresseEdit");
    agriculteurUi_.telephoneEdit = agriculteurRoot->findChild<QLineEdit *>("telephoneEdit");
    agriculteurUi_.emailEdit = agriculteurRoot->findChild<QLineEdit *>("emailEdit");
    agriculteurUi_.superficieEdit = agriculteurRoot->findChild<QLineEdit *>("superficieEdit");
    agriculteurUi_.oliviersEdit = agriculteurRoot->findChild<QLineEdit *>("oliviersEdit");
    agriculteurUi_.searchEdit = agriculteurRoot->findChild<QLineEdit *>("searchEdit");
    agriculteurUi_.tableWidget = agriculteurRoot->findChild<QTableWidget *>("tableWidget");
    agriculteurUi_.ajouterBtn = agriculteurRoot->findChild<QPushButton *>("ajouterBtn");
    agriculteurUi_.modifierBtn = agriculteurRoot->findChild<QPushButton *>("modifierBtn");
    agriculteurUi_.supprimerBtn = agriculteurRoot->findChild<QPushButton *>("supprimerBtn");
    agriculteurUi_.effacerBtn = agriculteurRoot->findChild<QPushButton *>("effacerBtn");
    agriculteurUi_.rechercherBtn = agriculteurRoot->findChild<QPushButton *>("rechercherBtn");
    agriculteurUi_.culturesBtn = agriculteurRoot->findChild<QPushButton *>("culturesBtn");
    agriculteurUi_.historiqueBtn = agriculteurRoot->findChild<QPushButton *>("historiqueBtn");
    agriculteurUi_.statsFormBtn = agriculteurRoot->findChild<QPushButton *>("statsFormBtn");
    agriculteurUi_.exportBtn = agriculteurRoot->findChild<QPushButton *>("exportBtn");

    return agriculteurUi_.idEdit && agriculteurUi_.cinEdit && agriculteurUi_.nomEdit
        && agriculteurUi_.tableWidget && agriculteurUi_.ajouterBtn && agriculteurUi_.modifierBtn
        && agriculteurUi_.supprimerBtn && agriculteurUi_.effacerBtn && agriculteurUi_.rechercherBtn;
}

void MainWindow::setupAgriculteurUi()
{
    QStackedWidget *stack = ui->centralwidget->findChild<QStackedWidget *>(QStringLiteral("stackedModules"));
    if (!stack) {
        statusBar()->showMessage(tr("StackedWidget introuvable."), 3000);
        return;
    }

    QWidget *pageAgriculteur = ui->centralwidget->findChild<QWidget *>(QStringLiteral("pageAgriculteur"));
    if (!pageAgriculteur) {
        for (int i = 0; i < stack->count(); ++i) {
            QWidget *w = stack->widget(i);
            if (w && w->objectName() == QStringLiteral("pageAgriculteur")) {
                pageAgriculteur = w;
                break;
            }
        }
    }

    QWidget *host = nullptr;
    QVBoxLayout *layout = nullptr;

    if (pageAgriculteur) {
        host = pageAgriculteur->findChild<QWidget *>(QStringLiteral("agriculteurHost"));
        if (host) {
            layout = qobject_cast<QVBoxLayout *>(host->layout());
        }
    }
    if (!host) {
        host = ui->centralwidget->findChild<QWidget *>(QStringLiteral("agriculteurHost"));
        if (host) layout = qobject_cast<QVBoxLayout *>(host->layout());
    }

    if (!pageAgriculteur || !host || !layout) {
        pageAgriculteur = new QWidget(this);
        pageAgriculteur->setObjectName(QStringLiteral("pageAgriculteur"));
        QVBoxLayout *pageLayout = new QVBoxLayout(pageAgriculteur);
        pageLayout->setContentsMargins(20, 20, 20, 20);

        QFrame *topBar = new QFrame(pageAgriculteur);
        topBar->setObjectName(QStringLiteral("topBarAgriculteur"));
        topBar->setMinimumHeight(60);
        topBar->setMaximumHeight(60);
        topBar->setStyleSheet(QStringLiteral(
            "QFrame#topBarAgriculteur { background: qlineargradient(x1:0, y1:0, x2:1, y2:0, stop:0 #243C53, stop:1 #2B5978); border-radius: 10px; }"
            "QLabel { color: #FFFFFF; font-size: 17px; font-weight: 700; }"
        ));
        QHBoxLayout *topBarLayout = new QHBoxLayout(topBar);
        QLabel *titleLabel = new QLabel(tr("AGRICULTEUR"), topBar);
        titleLabel->setObjectName(QStringLiteral("agriculteurTitle"));
        topBarLayout->addWidget(titleLabel);
        topBarLayout->addStretch();
        QLabel *userLabel = new QLabel(tr("Connecté : Opérateur Production"), topBar);
        userLabel->setStyleSheet(QStringLiteral("color: #E6EEF4; font-size: 12px;"));
        topBarLayout->addWidget(userLabel);
        pageLayout->addWidget(topBar);

        host = new QWidget(pageAgriculteur);
        host->setObjectName(QStringLiteral("agriculteurHost"));
        layout = new QVBoxLayout(host);
        layout->setContentsMargins(0, 0, 0, 0);
        pageLayout->addWidget(host, 1);

        const int insertIndex = 3;
        if (stack->count() >= insertIndex) {
            stack->insertWidget(insertIndex, pageAgriculteur);
        } else {
            stack->addWidget(pageAgriculteur);
        }
    }

    agriculteurPage_ = pageAgriculteur;

    QUiLoader loader;
    const QString appDir = QCoreApplication::applicationDirPath();
    const QString projRoot = QDir::current().filePath(QStringLiteral("."));
    const QStringList uiCandidates = {
        projRoot + QStringLiteral("/agriculteur/mainwindow.ui"),
        QDir::cleanPath(projRoot + QStringLiteral("/../agriculteur/mainwindow.ui")),
        appDir + QStringLiteral("/agriculteur/mainwindow.ui"),
        appDir + QStringLiteral("/../agriculteur/mainwindow.ui"),
        appDir + QStringLiteral("/../../agriculteur/mainwindow.ui"),
        appDir + QStringLiteral("/../../../agriculteur/mainwindow.ui"),
        appDir + QStringLiteral("/../../../../agriculteur/mainwindow.ui")
    };

    QWidget *loaded = nullptr;
    QString loadedPath;
    auto hideAgriculteurChrome = [](QWidget *root) {
        if (!root) return;
        for (QWidget *w : root->findChildren<QWidget *>()) {
            const QString name = w->objectName().toLower();
            if (name.contains("sidebar") || name == "agribtn" || name == "statsbtn"
                || name == "settingsbtn" || name == "logoutbtn" || name == "titlelabel") {
                w->setVisible(false);
            }
        }
    };

    for (const QString &path : uiCandidates) {
        QFile file(path);
        if (file.open(QFile::ReadOnly)) {
            loaded = loader.load(&file, host);
            file.close();
            if (loaded) {
                loadedPath = QDir::cleanPath(path);
                break;
            }
        }
    }

    if (auto *mw = qobject_cast<QMainWindow *>(loaded)) {
        QWidget *central = mw->takeCentralWidget();
        mw->deleteLater();
        loaded = central;
    }

    if (loaded) {
        loaded->setParent(host);
        loaded->setSizePolicy(QSizePolicy::Expanding, QSizePolicy::Expanding);
        loaded->setVisible(true);
        layout->addWidget(loaded, 1);
        agriculteurRoot = loaded;
        hideAgriculteurChrome(agriculteurRoot);
        agriculteurUi_.root = agriculteurRoot;
        applyAgriculteurTheme();

        if (resolveAgriculteurWidgets()) {
            agriculteurChargerDonnees();
            connect(agriculteurUi_.ajouterBtn, &QPushButton::clicked, this, &MainWindow::agriculteurOnAjouter);
            connect(agriculteurUi_.modifierBtn, &QPushButton::clicked, this, &MainWindow::agriculteurOnModifier);
            connect(agriculteurUi_.supprimerBtn, &QPushButton::clicked, this, &MainWindow::agriculteurOnSupprimer);
            connect(agriculteurUi_.effacerBtn, &QPushButton::clicked, this, &MainWindow::agriculteurClearForm);
            connect(agriculteurUi_.rechercherBtn, &QPushButton::clicked, this, &MainWindow::agriculteurOnRechercher);
            connect(agriculteurUi_.tableWidget, &QTableWidget::itemSelectionChanged, this, &MainWindow::agriculteurTableSelectionChanged);
            if (agriculteurUi_.culturesBtn) connect(agriculteurUi_.culturesBtn, &QPushButton::clicked, this, &MainWindow::agriculteurOnAfficherCultures);
            if (agriculteurUi_.historiqueBtn) connect(agriculteurUi_.historiqueBtn, &QPushButton::clicked, this, &MainWindow::agriculteurOnAfficherHistorique);
            if (agriculteurUi_.statsFormBtn) connect(agriculteurUi_.statsFormBtn, &QPushButton::clicked, this, &MainWindow::agriculteurOnAfficherStats);
            if (agriculteurUi_.exportBtn) connect(agriculteurUi_.exportBtn, &QPushButton::clicked, this, &MainWindow::agriculteurOnExportPDF);
        }
        statusBar()->showMessage(tr("Module Agriculteur chargé: %1").arg(loadedPath), 3000);
    } else {
        auto *label = new QLabel(tr("Impossible de charger l'UI Agriculteur."));
        label->setWordWrap(true);
        label->setStyleSheet("color:#B71C1C; font-weight:600;");
        layout->addWidget(label);
        statusBar()->showMessage(tr("UI Agriculteur introuvable."), 5000);
    }
}

void MainWindow::applyAgriculteurTheme()
{
    if (!agriculteurRoot) {
        return;
    }
    const QString style =
        "QWidget {"
        "  font-family: 'Segoe UI';"
        "  font-size: 12px;"
        "  background: #F4EEE4;"
        "  color: #1F2937;"
        "}"
        "QGroupBox {"
        "  font-weight: bold;"
        "  border: 2px solid #2B5978;"
        "  border-radius: 8px;"
        "  margin-top: 14px;"
        "  padding-top: 12px;"
        "  background: #FFFFFF;"
        "}"
        "QGroupBox::title {"
        "  subcontrol-origin: margin;"
        "  left: 12px;"
        "  padding: 0 6px;"
        "  color: #245B7A;"
        "}"
        "QLabel {"
        "  color: #1F2937;"
        "}"
        "QLineEdit, QComboBox, QDateEdit, QSpinBox {"
        "  background: #FFFBF5;"
        "  border: 2px solid #2B5978;"
        "  border-radius: 6px;"
        "  padding: 6px 10px;"
        "  color: #333;"
        "}"
        "QPushButton {"
        "  background: #6B8E23;"
        "  color: #FFFFFF;"
        "  border: none;"
        "  border-radius: 6px;"
        "  padding: 8px 14px;"
        "  font-weight: 600;"
        "}"
        "QPushButton#supprimerBtn {"
        "  background: #C62828;"
        "  color: #FFFFFF;"
        "}"
        "QPushButton#supprimerBtn:hover {"
        "  background: #B71C1C;"
        "}"
        "QPushButton#effacerBtn {"
        "  background: #F2B705;"
        "  color: #2B2B2B;"
        "}"
        "QPushButton#effacerBtn:hover {"
        "  background: #E0A800;"
        "  color: #2B2B2B;"
        "}"
        "QPushButton#exportBtn, QPushButton#statsFormBtn {"
        "  background: #F2B705;"
        "  color: #2B2B2B;"
        "}"
        "QPushButton#exportBtn:hover, QPushButton#statsFormBtn:hover {"
        "  background: #E0A800;"
        "  color: #2B2B2B;"
        "}"
        "QPushButton:hover {"
        "  background: #5A7A1E;"
        "  color: #FFFFFF;"
        "}"
        "QPushButton#ajouterBtn, QPushButton#modifierBtn, QPushButton#effacerBtn,"
        "QPushButton#supprimerBtn, QPushButton#culturesBtn, QPushButton#historiqueBtn,"
        "QPushButton#statsFormBtn, QPushButton#exportBtn, QPushButton#rechercherBtn {"
        "  min-height: 42px;"
        "  min-width: 120px;"
        "  padding: 10px 18px;"
        "  font-size: 13px;"
        "  font-weight: 600;"
        "  color: #FFFFFF;"
        "}"
        "QPushButton#effacerBtn, QPushButton#exportBtn, QPushButton#statsFormBtn {"
        "  color: #2B2B2B;"
        "}"
        "QTableWidget {"
        "  background: #FFFFFF;"
        "  border: 1px solid #E3D8C7;"
        "  border-radius: 10px;"
        "  gridline-color: #E3D8C7;"
        "  selection-background-color: #FFE9B5;"
        "  selection-color: #000000;"
        "}"
        "QHeaderView::section {"
        "  background: #2B5978;"
        "  color: #FFFFFF;"
        "  padding: 8px;"
        "  border: none;"
        "  font-weight: 600;"
        "}";
    agriculteurRoot->setStyleSheet(style);

    auto ensureActionButton = [this](const char *name, const QString &text) {
        if (QPushButton *btn = agriculteurRoot->findChild<QPushButton *>(name)) {
            btn->setMinimumHeight(42);
            btn->setMinimumWidth(120);
            if (!text.isEmpty()) {
                btn->setText(text);
            }
        }
    };
    ensureActionButton("ajouterBtn", tr("Ajouter"));
    ensureActionButton("modifierBtn", tr("Modifier"));
    ensureActionButton("effacerBtn", tr("Effacer"));
    ensureActionButton("supprimerBtn", tr("Supprimer"));
    ensureActionButton("culturesBtn", tr("Cultures"));
    ensureActionButton("historiqueBtn", tr("Historique"));
    ensureActionButton("statsFormBtn", tr("Statistiques"));
    ensureActionButton("exportBtn", tr("Exporter"));
    ensureActionButton("rechercherBtn", tr("Rechercher"));
}

void MainWindow::agriculteurChargerDonnees()
{
    agriculteurs_.clear();
    cultures_.clear();
    historique_.clear();
    agriculteurs_.append(Agriculteur(1, "AB123456", "Ahmed Mohamed", "Sfax", "98123456", "ahmed@email.com", "50", "1000"));
    agriculteurs_.append(Agriculteur(2, "CD789012", "Fatima Ali", "Sousse", "98234567", "fatima@email.com", "75", "1500"));
    agriculteurs_.append(Agriculteur(3, "EF345678", "Youssef Hassan", "Kairouan", "98345678", "youssef@email.com", "120", "2500"));
    cultures_.append(Culture(1, 1, "Oliviers Chemlali", "Olives", "30", QDate(2020, 4, 15), "Chemlali", "Productif"));
    cultures_.append(Culture(2, 1, "Oliviers Koroneiki", "Olives", "20", QDate(2021, 5, 10), "Koroneiki", "En développement"));
    cultures_.append(Culture(3, 2, "Oliviers Manzanillo", "Olives", "50", QDate(2019, 3, 20), "Manzanillo", "Productif"));
    cultures_.append(Culture(4, 3, "Oliviers Frantoio", "Olives", "70", QDate(2018, 4, 5), "Frantoio", "Productif"));
    cultures_.append(Culture(5, 3, "Oliviers Arbequina", "Olives", "50", QDate(2021, 6, 1), "Arbequina", "En développement"));
    historique_.append(Historique(1, 1, "CREATION", "Agriculteur créé", QDateTime(QDate(2023, 1, 15), QTime(10, 30)), "Admin"));
    historique_.append(Historique(2, 1, "MODIFICATION", "Mise à jour téléphone", QDateTime(QDate(2023, 6, 20), QTime(14, 45)), "Admin"));
    historique_.append(Historique(3, 2, "CREATION", "Agriculteur créé", QDateTime(QDate(2023, 2, 10), QTime(11, 0)), "Admin"));

    if (agriculteurUi_.tableWidget) {
        agriculteurUi_.tableWidget->setColumnCount(8);
        agriculteurUi_.tableWidget->setHorizontalHeaderLabels(
            QStringList() << tr("ID") << tr("CIN") << tr("Nom") << tr("Adresse") << tr("Téléphone") << tr("Email") << tr("Superficie (ha)") << tr("Nombre d'Oliviers"));
        agriculteurUi_.tableWidget->setAlternatingRowColors(true);
        agriculteurUi_.tableWidget->setSelectionBehavior(QAbstractItemView::SelectRows);
        agriculteurUi_.tableWidget->horizontalHeader()->setSectionResizeMode(QHeaderView::Stretch);
        agriculteurAfficherTableau();
    }
}

void MainWindow::agriculteurAfficherTableau()
{
    if (!agriculteurUi_.tableWidget) return;
    agriculteurUi_.tableWidget->setRowCount(0);
    for (int i = 0; i < agriculteurs_.size(); ++i) {
        const Agriculteur &ag = agriculteurs_.at(i);
        agriculteurUi_.tableWidget->insertRow(i);
        agriculteurUi_.tableWidget->setItem(i, 0, new QTableWidgetItem(QString::number(ag.getId())));
        agriculteurUi_.tableWidget->setItem(i, 1, new QTableWidgetItem(ag.getCin()));
        agriculteurUi_.tableWidget->setItem(i, 2, new QTableWidgetItem(ag.getNom()));
        agriculteurUi_.tableWidget->setItem(i, 3, new QTableWidgetItem(ag.getAdresse()));
        agriculteurUi_.tableWidget->setItem(i, 4, new QTableWidgetItem(ag.getTelephone()));
        agriculteurUi_.tableWidget->setItem(i, 5, new QTableWidgetItem(ag.getEmail()));
        agriculteurUi_.tableWidget->setItem(i, 6, new QTableWidgetItem(ag.getSuperficie()));
        agriculteurUi_.tableWidget->setItem(i, 7, new QTableWidgetItem(ag.getOliviers()));
    }
}

void MainWindow::agriculteurAfficherDansForm(const Agriculteur &ag)
{
    if (agriculteurUi_.idEdit) agriculteurUi_.idEdit->setText(QString::number(ag.getId()));
    if (agriculteurUi_.cinEdit) agriculteurUi_.cinEdit->setText(ag.getCin());
    if (agriculteurUi_.nomEdit) agriculteurUi_.nomEdit->setText(ag.getNom());
    if (agriculteurUi_.adresseEdit) agriculteurUi_.adresseEdit->setText(ag.getAdresse());
    if (agriculteurUi_.telephoneEdit) agriculteurUi_.telephoneEdit->setText(ag.getTelephone());
    if (agriculteurUi_.emailEdit) agriculteurUi_.emailEdit->setText(ag.getEmail());
    if (agriculteurUi_.superficieEdit) agriculteurUi_.superficieEdit->setText(ag.getSuperficie());
    if (agriculteurUi_.oliviersEdit) agriculteurUi_.oliviersEdit->setText(ag.getOliviers());
}

void MainWindow::agriculteurAjouterHistorique(int agriculteurId, const QString &action, const QString &details)
{
    static int hId = 10;
    historique_.append(Historique(hId++, agriculteurId, action, details, QDateTime::currentDateTime(), "Admin"));
}

void MainWindow::agriculteurOnAjouter()
{
    if (!agriculteurUi_.cinEdit || !agriculteurUi_.nomEdit) return;
    if (agriculteurUi_.cinEdit->text().trimmed().isEmpty() || agriculteurUi_.nomEdit->text().trimmed().isEmpty()) {
        QMessageBox::warning(this, tr("Erreur"), tr("Veuillez remplir au moins CIN et Nom!"));
        return;
    }
    int newId = agriculteurs_.isEmpty() ? 1 : agriculteurs_.last().getId() + 1;
    Agriculteur newAg(newId, agriculteurUi_.cinEdit->text().trimmed(), agriculteurUi_.nomEdit->text().trimmed(),
                     agriculteurUi_.adresseEdit ? agriculteurUi_.adresseEdit->text().trimmed() : QString(),
                     agriculteurUi_.telephoneEdit ? agriculteurUi_.telephoneEdit->text().trimmed() : QString(),
                     agriculteurUi_.emailEdit ? agriculteurUi_.emailEdit->text().trimmed() : QString(),
                     agriculteurUi_.superficieEdit ? agriculteurUi_.superficieEdit->text().trimmed() : QString(),
                     agriculteurUi_.oliviersEdit ? agriculteurUi_.oliviersEdit->text().trimmed() : QString());
    agriculteurs_.append(newAg);
    agriculteurAjouterHistorique(newId, "CREATION", "Agriculteur: " + newAg.getNom());
    agriculteurAfficherTableau();
    agriculteurClearForm();
    QMessageBox::information(this, tr("Succès"), tr("Agriculteur ajouté avec succès!"));
}

void MainWindow::agriculteurOnModifier()
{
    if (agriculteurSelectedIndex < 0) {
        QMessageBox::warning(this, tr("Erreur"), tr("Veuillez sélectionner un agriculteur à modifier!"));
        return;
    }
    if (!agriculteurUi_.cinEdit || !agriculteurUi_.nomEdit) return;
    int agricId = agriculteurs_[agriculteurSelectedIndex].getId();
    agriculteurs_[agriculteurSelectedIndex].setCin(agriculteurUi_.cinEdit->text().trimmed());
    agriculteurs_[agriculteurSelectedIndex].setNom(agriculteurUi_.nomEdit->text().trimmed());
    if (agriculteurUi_.adresseEdit) agriculteurs_[agriculteurSelectedIndex].setAdresse(agriculteurUi_.adresseEdit->text().trimmed());
    if (agriculteurUi_.telephoneEdit) agriculteurs_[agriculteurSelectedIndex].setTelephone(agriculteurUi_.telephoneEdit->text().trimmed());
    if (agriculteurUi_.emailEdit) agriculteurs_[agriculteurSelectedIndex].setEmail(agriculteurUi_.emailEdit->text().trimmed());
    if (agriculteurUi_.superficieEdit) agriculteurs_[agriculteurSelectedIndex].setSuperficie(agriculteurUi_.superficieEdit->text().trimmed());
    if (agriculteurUi_.oliviersEdit) agriculteurs_[agriculteurSelectedIndex].setOliviers(agriculteurUi_.oliviersEdit->text().trimmed());
    agriculteurAjouterHistorique(agricId, "MODIFICATION", "Mise à jour: " + agriculteurUi_.nomEdit->text());
    agriculteurAfficherTableau();
    agriculteurClearForm();
    QMessageBox::information(this, tr("Succès"), tr("Agriculteur modifié avec succès!"));
}

void MainWindow::agriculteurOnSupprimer()
{
    if (agriculteurSelectedIndex < 0) {
        QMessageBox::warning(this, tr("Erreur"), tr("Veuillez sélectionner un agriculteur à supprimer!"));
        return;
    }
    QMessageBox::StandardButton reply = QMessageBox::question(this, tr("Confirmation"),
        tr("Êtes-vous sûr de vouloir supprimer cet agriculteur?"),
        QMessageBox::Yes | QMessageBox::No, QMessageBox::No);
    if (reply == QMessageBox::Yes) {
        int agricId = agriculteurs_[agriculteurSelectedIndex].getId();
        QString nomAg = agriculteurs_[agriculteurSelectedIndex].getNom();
        agriculteurAjouterHistorique(agricId, "SUPPRESSION", "Agriculteur supprimé: " + nomAg);
        agriculteurs_.removeAt(agriculteurSelectedIndex);
        agriculteurAfficherTableau();
        agriculteurClearForm();
        QMessageBox::information(this, tr("Succès"), tr("Agriculteur supprimé avec succès!"));
    }
}

void MainWindow::agriculteurOnRechercher()
{
    if (!agriculteurUi_.searchEdit || !agriculteurUi_.tableWidget) return;
    QString searchText = agriculteurUi_.searchEdit->text().trimmed().toLower();
    if (searchText.isEmpty()) {
        agriculteurAfficherTableau();
        return;
    }
    agriculteurUi_.tableWidget->setRowCount(0);
    int rowCount = 0;
    for (int i = 0; i < agriculteurs_.size(); ++i) {
        const Agriculteur &ag = agriculteurs_.at(i);
        if (ag.getNom().toLower().contains(searchText) || ag.getCin().toLower().contains(searchText)) {
            agriculteurUi_.tableWidget->insertRow(rowCount);
            agriculteurUi_.tableWidget->setItem(rowCount, 0, new QTableWidgetItem(QString::number(ag.getId())));
            agriculteurUi_.tableWidget->setItem(rowCount, 1, new QTableWidgetItem(ag.getCin()));
            agriculteurUi_.tableWidget->setItem(rowCount, 2, new QTableWidgetItem(ag.getNom()));
            agriculteurUi_.tableWidget->setItem(rowCount, 3, new QTableWidgetItem(ag.getAdresse()));
            agriculteurUi_.tableWidget->setItem(rowCount, 4, new QTableWidgetItem(ag.getTelephone()));
            agriculteurUi_.tableWidget->setItem(rowCount, 5, new QTableWidgetItem(ag.getEmail()));
            agriculteurUi_.tableWidget->setItem(rowCount, 6, new QTableWidgetItem(ag.getSuperficie()));
            agriculteurUi_.tableWidget->setItem(rowCount, 7, new QTableWidgetItem(ag.getOliviers()));
            ++rowCount;
        }
    }
}

void MainWindow::agriculteurTableSelectionChanged()
{
    if (!agriculteurUi_.tableWidget || agriculteurUi_.tableWidget->selectedItems().isEmpty()) {
        agriculteurSelectedIndex = -1;
        return;
    }
    int row = agriculteurUi_.tableWidget->row(agriculteurUi_.tableWidget->selectedItems().first());
    QString cin = agriculteurUi_.tableWidget->item(row, 1) ? agriculteurUi_.tableWidget->item(row, 1)->text() : QString();
    for (int i = 0; i < agriculteurs_.size(); ++i) {
        if (agriculteurs_[i].getCin() == cin) {
            agriculteurSelectedIndex = i;
            agriculteurAfficherDansForm(agriculteurs_[i]);
            return;
        }
    }
}

void MainWindow::agriculteurClearForm()
{
    if (agriculteurUi_.idEdit) agriculteurUi_.idEdit->clear();
    if (agriculteurUi_.cinEdit) agriculteurUi_.cinEdit->clear();
    if (agriculteurUi_.nomEdit) agriculteurUi_.nomEdit->clear();
    if (agriculteurUi_.adresseEdit) agriculteurUi_.adresseEdit->clear();
    if (agriculteurUi_.telephoneEdit) agriculteurUi_.telephoneEdit->clear();
    if (agriculteurUi_.emailEdit) agriculteurUi_.emailEdit->clear();
    if (agriculteurUi_.superficieEdit) agriculteurUi_.superficieEdit->clear();
    if (agriculteurUi_.oliviersEdit) agriculteurUi_.oliviersEdit->clear();
    if (agriculteurUi_.searchEdit) agriculteurUi_.searchEdit->clear();
    agriculteurSelectedIndex = -1;
    if (agriculteurUi_.tableWidget) agriculteurUi_.tableWidget->clearSelection();
}

void MainWindow::agriculteurOnAfficherCultures()
{
    if (agriculteurSelectedIndex < 0) {
        QMessageBox::warning(this, tr("Aucune sélection"), tr("Veuillez sélectionner un agriculteur"));
        return;
    }
    const Agriculteur &ag = agriculteurs_.at(agriculteurSelectedIndex);
    QString msg = QString(tr("Cultures de : %1\n\n")).arg(ag.getNom());
    bool found = false;
    for (const Culture &c : cultures_) {
        if (c.getAgriculteurId() == ag.getId()) {
            found = true;
            msg += QString(tr("- %1 (%2)\n  Type: %3 | Surface: %4 ha\n  Variété: %5 | État: %6\n"))
                .arg(c.getNom(), c.getType(), c.getSuperficie(), c.getVariete(), c.getEtat());
        }
    }
    if (!found) msg += tr("Aucune culture enregistrée");
    QMessageBox::information(this, tr("Cultures"), msg);
}

void MainWindow::agriculteurOnAfficherHistorique()
{
    if (agriculteurSelectedIndex < 0) {
        QMessageBox::warning(this, tr("Aucune sélection"), tr("Veuillez sélectionner un agriculteur"));
        return;
    }
    const Agriculteur &ag = agriculteurs_.at(agriculteurSelectedIndex);
    QString msg = QString(tr("Historique : %1\n\n")).arg(ag.getNom());
    bool found = false;
    for (const Historique &h : historique_) {
        if (h.getAgriculteurId() == ag.getId()) {
            found = true;
            msg += QString(tr("[%1] %2\n  Action: %3\n  Détails: %4\n\n"))
                .arg(h.getDateHeure().toString("dd/MM/yyyy hh:mm:ss"), h.getUtilisateur(), h.getAction(), h.getDetails());
        }
    }
    if (!found) msg += tr("Aucun historique");
    QMessageBox::information(this, tr("Historique"), msg);
}

void MainWindow::agriculteurOnAfficherStats()
{
    QString stats = tr("📊 STATISTIQUES GÉNÉRALES\n\n");
    stats += QString(tr("Nombre total d'agriculteurs: %1\n")).arg(agriculteurs_.size());
    stats += QString(tr("Nombre total de cultures: %1\n")).arg(cultures_.size());
    double superficieTotale = 0;
    int oliviersTotaux = 0;
    for (const Agriculteur &ag : agriculteurs_) {
        superficieTotale += ag.getSuperficie().toDouble();
        oliviersTotaux += ag.getOliviers().toInt();
    }
    stats += QString(tr("\nSuperficie totale: %1 hectares\n")).arg(superficieTotale, 0, 'f', 2);
    stats += QString(tr("Nombre total d'oliviers: %1\n\n")).arg(oliviersTotaux);
    stats += tr("Cultures par type:\n");
    QMap<QString, int> culturesParType;
    for (const Culture &c : cultures_) culturesParType[c.getType()]++;
    for (auto it = culturesParType.begin(); it != culturesParType.end(); ++it)
        stats += QString(tr("- %1: %2 parcelles\n")).arg(it.key()).arg(it.value());
    if (!agriculteurs_.isEmpty()) {
        stats += tr("\nTop agriculteurs (par nombre de cultures):\n");
        QMap<int, int> culturesParAgriculteur;
        for (const Culture &c : cultures_) culturesParAgriculteur[c.getAgriculteurId()]++;
        int count = 0;
        for (auto it = culturesParAgriculteur.begin(); it != culturesParAgriculteur.end() && count < 3; ++it) {
            for (const Agriculteur &a : agriculteurs_) {
                if (a.getId() == it.key()) {
                    stats += QString(tr("- %1: %2 cultures\n")).arg(a.getNom()).arg(it.value());
                    ++count;
                    break;
                }
            }
        }
    }
    QMessageBox::information(this, tr("Statistiques"), stats);
}

void MainWindow::agriculteurOnExportPDF()
{
    QString fileName = QFileDialog::getSaveFileName(this, tr("Exporter en PDF"), "", tr("PDF Files (*.pdf)"));
    if (!fileName.isEmpty()) {
        if (!fileName.endsWith(".pdf", Qt::CaseInsensitive)) fileName += ".pdf";
        QMessageBox::information(this, tr("Export"), tr("PDF exporté: ") + fileName);
    }
}
void MainWindow::exportExtractionPdf()
{
    const QString filePath = QFileDialog::getSaveFileName(
        this,
        tr("Exporter PDF"),
        QStringLiteral("extraction_%1.pdf").arg(QDateTime::currentDateTime().toString("yyyyMMdd_HHmmss")),
        tr("PDF Files (*.pdf)")
    );
    if (filePath.isEmpty()) {
        return;
    }

    QPrinter printer(QPrinter::HighResolution);
    printer.setOutputFormat(QPrinter::PdfFormat);
    printer.setOutputFileName(filePath);
    printer.setPageMargins(QMarginsF(12, 12, 12, 12));

    QPainter painter(&printer);
    if (!painter.isActive()) {
        QMessageBox::warning(this, tr("Erreur"), tr("Impossible de créer le PDF."));
        return;
    }

    const int left = 40;
    int y = 40;
    painter.setFont(QFont("Segoe UI", 12, QFont::Bold));
    painter.drawText(left, y, tr("Historique des Productions"));
    y += 30;

    painter.setFont(QFont("Segoe UI", 9));
    const int rowHeight = 20;
    const int colCount = ui->table_ext->columnCount();
    const int tableWidth = printer.pageRect(QPrinter::DevicePixel).width() - 2 * left;
    const int colWidth = colCount > 0 ? tableWidth / colCount : tableWidth;

    auto drawRow = [&](const QStringList &cells, bool header) {
        if (header) {
            painter.setFont(QFont("Segoe UI", 9, QFont::Bold));
        } else {
            painter.setFont(QFont("Segoe UI", 9));
        }
        int x = left;
        for (const QString &cell : cells) {
            painter.drawRect(x, y, colWidth, rowHeight);
            painter.drawText(x + 4, y + 14, cell);
            x += colWidth;
        }
        y += rowHeight;
    };

    QStringList headers;
    for (int c = 0; c < colCount; ++c) {
        headers << ui->table_ext->horizontalHeaderItem(c)->text();
    }
    drawRow(headers, true);

    for (int r = 0; r < ui->table_ext->rowCount(); ++r) {
        if (ui->table_ext->isRowHidden(r)) {
            continue;
        }
        QStringList cells;
        for (int c = 0; c < colCount; ++c) {
            QTableWidgetItem *item = ui->table_ext->item(r, c);
            cells << (item ? item->text() : QString());
        }
        if (y + rowHeight > printer.pageRect(QPrinter::DevicePixel).height() - 40) {
            printer.newPage();
            y = 40;
            drawRow(headers, true);
        }
        drawRow(cells, false);
    }

    painter.end();
    QMessageBox::information(this, tr("Export PDF"), tr("PDF exporté avec succès."));
}

void MainWindow::showExtractionStats()
{
    QMap<QString, int> counts;
    const int typeCol = 2;
    for (int r = 0; r < ui->table_ext->rowCount(); ++r) {
        if (ui->table_ext->isRowHidden(r)) {
            continue;
        }
        QTableWidgetItem *item = ui->table_ext->item(r, typeCol);
        const QString key = item ? item->text().trimmed() : tr("Inconnu");
        counts[key] += 1;
    }

    QStringList lines;
    for (auto it = counts.constBegin(); it != counts.constEnd(); ++it) {
        lines << QString("%1 : %2").arg(it.key(), QString::number(it.value()));
    }

    QMessageBox::information(
        this,
        tr("Statistiques"),
        lines.isEmpty() ? tr("Aucune donnée disponible.") : lines.join("\n")
    );
}

MainWindow::~MainWindow()
{
    delete ui;
}











