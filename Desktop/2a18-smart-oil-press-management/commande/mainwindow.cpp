#include "mainwindow.h"
#include "ui_mainwindow.h"

#include <QHeaderView>
#include <QTableWidgetItem>
#include <QMessageBox>
#include <QFileDialog>
#include <QFile>
#include <QTextStream>

MainWindow::MainWindow(QWidget *parent)
    : QMainWindow(parent)
    , ui(new Ui::MainWindow)
{
    ui->setupUi(this);
    applyStyle();
    setupTable();

    ui->navActive->setProperty("navState", "active");
    ui->navButton_2->setProperty("navState", "normal");
    ui->navButton_3->setProperty("navState", "normal");
    ui->navButton_4->setProperty("navState", "normal");
    ui->navAccent->setProperty("navState", "normal");

    connect(ui->addButton, &QPushButton::clicked, this, &MainWindow::onAddClicked);
    connect(ui->updateButton, &QPushButton::clicked, this, &MainWindow::onUpdateClicked);
    connect(ui->deleteButton, &QPushButton::clicked, this, &MainWindow::onDeleteClicked);
    connect(ui->clearButton, &QPushButton::clicked, this, &MainWindow::onClearClicked);

    connect(ui->filterButton, &QPushButton::clicked, this, &MainWindow::onFilterClicked);
    connect(ui->resetFilterButton, &QPushButton::clicked, this, &MainWindow::onResetFilterClicked);

    connect(ui->sortButton, &QPushButton::clicked, this, &MainWindow::onSortClicked);
    connect(ui->exportButton, &QPushButton::clicked, this, &MainWindow::onExportClicked);

    connect(ui->addQuickButton, &QPushButton::clicked, this, &MainWindow::onQuickAddClicked);
    connect(ui->tableWidget, &QTableWidget::itemSelectionChanged, this, &MainWindow::onTableSelectionChanged);
}

MainWindow::~MainWindow()
{
    delete ui;
}

void MainWindow::applyStyle()
{
    setStyleSheet(
        "QWidget {"
        "  font-family: 'Segoe UI';"
        "  font-size: 14px;"
        "  background: #F5F5F5;"
        "  color: #1F2937;"
        "}"
        "#topBar {"
        "  background: #1F4788;"
        "  border-radius: 10px;"
        "}"
        "QFrame#sidebar {"
        "  background: qlineargradient(x1:0, y1:0, x2:0, y2:1,"
        "    stop:0 #5E8C2A, stop:1 #4C7A22);"
        "  border-right: 1px solid #3E5F1A;"
        "}"
        "QLabel#sidebarTitle {"
        "  color: #F5F5F5;"
        "  font-weight: 700;"
        "  font-size: 13px;"
        "  margin-bottom: 10px;"
        "}"
        "#titleLabel {"
        "  color: #FBC02D;"
        "  font-size: 22px;"
        "  font-weight: 700;"
        "}"
        "#subTitleLabel {"
        "  color: #E8ECF4;"
        "  font-size: 12px;"
        "}"
        "#filterBar, #tableActionBar {"
        "  background: #FFFFFF;"
        "  border-radius: 10px;"
        "  border: 1px solid #E0E0E0;"
        "}"
        "#detailsGroup {"
        "  background: #FFFFFF;"
        "  border-radius: 10px;"
        "  border: 1px solid #E0E0E0;"
        "  margin-top: 6px;"
        "}"
        "QLineEdit, QComboBox {"
        "  background: #FFFFFF;"
        "  border: 1px solid #D0D0D0;"
        "  border-radius: 6px;"
        "  padding: 6px 10px;"
        "}"
        "QLineEdit:focus, QComboBox:focus {"
        "  border: 1px solid #2E7D32;"
        "}"
        "QPushButton {"
        "  border: none;"
        "  border-radius: 6px;"
        "  padding: 8px 14px;"
        "  font-weight: 600;"
        "  background: #2E7D32;"
        "  color: white;"
        "}"
        "QPushButton#filterButton, QPushButton#exportButton {"
        "  background: #FBC02D;"
        "  color: #2B2B2B;"
        "}"
        "QPushButton#resetFilterButton, QPushButton#clearButton {"
        "  background: #9E9E9E;"
        "  color: white;"
        "}"
        "QPushButton#deleteButton {"
        "  background: #C62828;"
        "  color: white;"
        "}"
        "QPushButton#navActive,"
        "QPushButton#navButton_2,"
        "QPushButton#navButton_3,"
        "QPushButton#navButton_4,"
        "QPushButton#navAccent {"
        "  text-align: left;"
        "  padding: 12px 16px;"
        "  border-radius: 10px;"
        "  font-weight: 600;"
        "}"
        "QPushButton[navState=\"normal\"] {"
        "  background-color: #3E6E1F;"
        "  color: #FFFFFF;"
        "  border: 2px solid #2F5E19;"
        "}"
        "QPushButton[navState=\"active\"] {"
        "  background-color: #F2B705;"
        "  color: #FFFFFF;"
        "  border: 2px solid #C99305;"
        "  font-weight: 700;"
        "}"
        "QPushButton:hover {"
        "  opacity: 0.92;"
        "}"
        "QTableWidget {"
        "  background: #FFFFFF;"
        "  border: 1px solid #E0E0E0;"
        "  border-radius: 10px;"
        "  gridline-color: #EAEAEA;"
        "  selection-background-color: #E8F5E9;"
        "  selection-color: #1F2937;"
        "}"
        "QHeaderView::section {"
        "  background: #1F4788;"
        "  color: white;"
        "  padding: 8px;"
        "  border: none;"
        "  font-weight: 600;"
        "}"
    );
}

void MainWindow::setupTable()
{
    ui->tableWidget->setColumnCount(10);
    ui->tableWidget->setHorizontalHeaderLabels({
        "ID Commande",
        "ID Agriculteur",
        "Date de commande",
        "Quantite commandee",
        "Prix unitaire",
        "Montant total",
        "ID Livreur",
        "Email",
        "Email Livreur",
        "Statut"
    });
    ui->tableWidget->horizontalHeader()->setSectionResizeMode(QHeaderView::Stretch);
    ui->tableWidget->setAlternatingRowColors(true);
    ui->tableWidget->setSelectionBehavior(QAbstractItemView::SelectRows);
    ui->tableWidget->setEditTriggers(QAbstractItemView::NoEditTriggers);
}

bool MainWindow::readForm(QString &id, QString &farmerId, QString &date,
                          double &qty, double &price, double &total,
                          QString &livreurId, QString &emailClient,
                          QString &emailLivreur, QString &status)
{
    id = ui->idLineEdit->text().trimmed();
    farmerId = ui->farmerLineEdit->text().trimmed();
    date = ui->dateLineEdit->text().trimmed();
    QString qtyStr = ui->qtyLineEdit->text().trimmed();
    QString priceStr = ui->priceLineEdit->text().trimmed();
    QString totalStr = ui->totalLineEdit->text().trimmed();
    livreurId = ui->livreurLineEdit->text().trimmed();
    emailClient = ui->emailLineEdit->text().trimmed();
    emailLivreur = ui->emailLivreurLineEdit->text().trimmed();
    status = ui->statusCombo->currentText().trimmed();

    if (id.isEmpty() || farmerId.isEmpty() || date.isEmpty() || qtyStr.isEmpty() || priceStr.isEmpty()) {
        QMessageBox::warning(this, "Champs manquants", "Veuillez remplir ID, agriculteur, date, quantite et prix.");
        return false;
    }

    bool okQty = false;
    bool okPrice = false;
    qty = qtyStr.toDouble(&okQty);
    price = priceStr.toDouble(&okPrice);
    if (!okQty || !okPrice) {
        QMessageBox::warning(this, "Valeurs invalides", "Quantite et prix doivent etre numeriques.");
        return false;
    }

    if (totalStr.isEmpty()) {
        total = qty * price;
    } else {
        bool okTotal = false;
        total = totalStr.toDouble(&okTotal);
        if (!okTotal) {
            QMessageBox::warning(this, "Valeur invalide", "Montant total doit etre numerique.");
            return false;
        }
    }

    return true;
}

void MainWindow::setRow(int row, const QString &id, const QString &farmerId, const QString &date,
                        double qty, double price, double total,
                        const QString &livreurId, const QString &emailClient,
                        const QString &emailLivreur, const QString &status)
{
    ui->tableWidget->setItem(row, 0, new QTableWidgetItem(id));
    ui->tableWidget->setItem(row, 1, new QTableWidgetItem(farmerId));
    ui->tableWidget->setItem(row, 2, new QTableWidgetItem(date));
    ui->tableWidget->setItem(row, 3, new QTableWidgetItem(formatNumber(qty)));
    ui->tableWidget->setItem(row, 4, new QTableWidgetItem(formatNumber(price)));
    ui->tableWidget->setItem(row, 5, new QTableWidgetItem(formatNumber(total)));
    ui->tableWidget->setItem(row, 6, new QTableWidgetItem(livreurId));
    ui->tableWidget->setItem(row, 7, new QTableWidgetItem(emailClient));
    ui->tableWidget->setItem(row, 8, new QTableWidgetItem(emailLivreur));
    ui->tableWidget->setItem(row, 9, new QTableWidgetItem(status));
}

int MainWindow::selectedRow() const
{
    auto rows = ui->tableWidget->selectionModel()->selectedRows();
    if (rows.isEmpty()) {
        return -1;
    }
    return rows.first().row();
}

void MainWindow::fillFormFromRow(int row)
{
    ui->idLineEdit->setText(ui->tableWidget->item(row, 0)->text());
    ui->farmerLineEdit->setText(ui->tableWidget->item(row, 1)->text());
    ui->dateLineEdit->setText(ui->tableWidget->item(row, 2)->text());
    ui->qtyLineEdit->setText(ui->tableWidget->item(row, 3)->text());
    ui->priceLineEdit->setText(ui->tableWidget->item(row, 4)->text());
    ui->totalLineEdit->setText(ui->tableWidget->item(row, 5)->text());
    ui->livreurLineEdit->setText(ui->tableWidget->item(row, 6)->text());
    ui->emailLineEdit->setText(ui->tableWidget->item(row, 7)->text());
    ui->emailLivreurLineEdit->setText(ui->tableWidget->item(row, 8)->text());

    QString status = ui->tableWidget->item(row, 9)->text();
    int index = ui->statusCombo->findText(status);
    if (index >= 0) {
        ui->statusCombo->setCurrentIndex(index);
    }
}

QString MainWindow::formatNumber(double value) const
{
    return QString::number(value, 'f', 2);
}

QString MainWindow::csvEscape(const QString &text) const
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

void MainWindow::filterRows(const QString &query, const QString &status)
{
    for (int row = 0; row < ui->tableWidget->rowCount(); ++row) {
        bool matchQuery = query.isEmpty();
        if (!matchQuery) {
            for (int col = 0; col < ui->tableWidget->columnCount(); ++col) {
                auto *item = ui->tableWidget->item(row, col);
                if (item && item->text().contains(query, Qt::CaseInsensitive)) {
                    matchQuery = true;
                    break;
                }
            }
        }

        bool matchStatus = (status == "Tous statuts");
        if (!matchStatus) {
            auto *statusItem = ui->tableWidget->item(row, 9);
            if (statusItem && statusItem->text().compare(status, Qt::CaseInsensitive) == 0) {
                matchStatus = true;
            }
        }

        ui->tableWidget->setRowHidden(row, !(matchQuery && matchStatus));
    }
}

void MainWindow::onAddClicked()
{
    QString id, farmerId, date, livreurId, emailClient, emailLivreur, status;
    double qty = 0.0, price = 0.0, total = 0.0;
    if (!readForm(id, farmerId, date, qty, price, total, livreurId, emailClient, emailLivreur, status)) {
        return;
    }

    int row = ui->tableWidget->rowCount();
    ui->tableWidget->insertRow(row);
    setRow(row, id, farmerId, date, qty, price, total, livreurId, emailClient, emailLivreur, status);

    onClearClicked();
}

void MainWindow::onUpdateClicked()
{
    int row = selectedRow();
    if (row < 0) {
        QMessageBox::warning(this, "Selection requise", "Selectionnez une ligne a modifier.");
        return;
    }

    QString id, farmerId, date, livreurId, emailClient, emailLivreur, status;
    double qty = 0.0, price = 0.0, total = 0.0;
    if (!readForm(id, farmerId, date, qty, price, total, livreurId, emailClient, emailLivreur, status)) {
        return;
    }

    setRow(row, id, farmerId, date, qty, price, total, livreurId, emailClient, emailLivreur, status);
}

void MainWindow::onDeleteClicked()
{
    int row = selectedRow();
    if (row < 0) {
        QMessageBox::warning(this, "Selection requise", "Selectionnez une ligne a supprimer.");
        return;
    }

    ui->tableWidget->removeRow(row);
    onClearClicked();
}

void MainWindow::onClearClicked()
{
    ui->idLineEdit->clear();
    ui->farmerLineEdit->clear();
    ui->dateLineEdit->clear();
    ui->qtyLineEdit->clear();
    ui->priceLineEdit->clear();
    ui->totalLineEdit->clear();
    ui->livreurLineEdit->clear();
    ui->emailLineEdit->clear();
    ui->emailLivreurLineEdit->clear();
    ui->statusCombo->setCurrentIndex(0);
    ui->tableWidget->clearSelection();
    ui->idLineEdit->setFocus();
}

void MainWindow::onFilterClicked()
{
    filterRows(ui->searchLineEdit->text().trimmed(), ui->filterStatusCombo->currentText().trimmed());
}

void MainWindow::onResetFilterClicked()
{
    ui->searchLineEdit->clear();
    ui->filterStatusCombo->setCurrentIndex(0);
    filterRows("", "Tous statuts");
}

void MainWindow::onSortClicked()
{
    int col = ui->sortColumnCombo->currentIndex();
    Qt::SortOrder order = (ui->sortOrderCombo->currentIndex() == 0)
        ? Qt::AscendingOrder
        : Qt::DescendingOrder;
    ui->tableWidget->sortItems(col, order);
}

void MainWindow::onExportClicked()
{
    QString filePath = QFileDialog::getSaveFileName(this, "Ecrire CSV", "commandes.csv", "CSV Files (*.csv)");
    if (filePath.isEmpty()) {
        return;
    }

    QFile file(filePath);
    if (!file.open(QIODevice::WriteOnly | QIODevice::Text)) {
        QMessageBox::warning(this, "Erreur", "Impossible d'ouvrir le fichier pour ecriture.");
        return;
    }

    QTextStream out(&file);
    QStringList headers;
    for (int col = 0; col < ui->tableWidget->columnCount(); ++col) {
        headers << csvEscape(ui->tableWidget->horizontalHeaderItem(col)->text());
    }
    out << headers.join(',') << "\n";

    for (int row = 0; row < ui->tableWidget->rowCount(); ++row) {
        if (ui->tableWidget->isRowHidden(row)) {
            continue;
        }
        QStringList rowData;
        for (int col = 0; col < ui->tableWidget->columnCount(); ++col) {
            auto *item = ui->tableWidget->item(row, col);
            rowData << csvEscape(item ? item->text() : "");
        }
        out << rowData.join(',') << "\n";
    }

    file.close();
    QMessageBox::information(this, "Export termine", "Le fichier CSV a ete ecrit avec succes.");
}

void MainWindow::onTableSelectionChanged()
{
    int row = selectedRow();
    if (row >= 0) {
        fillFormFromRow(row);
    }
}

void MainWindow::onQuickAddClicked()
{
    onClearClicked();
}
