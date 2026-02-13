#include "mainwindow.h"
#include "ui_mainwindow.h"

#include <QAbstractItemView>
#include <QComboBox>
#include <QDateEdit>
#include <QDialog>
#include <QDialogButtonBox>
#include <QFormLayout>
#include <QHeaderView>
#include <QItemSelectionModel>
#include <QLineEdit>
#include <QMessageBox>
#include <QFile>
#include <QFileDialog>
#include <QTextStream>
#include <QSpinBox>
#include <QTableWidgetItem>
#include <QStyle>

MainWindow::MainWindow(QWidget *parent)
    : QMainWindow(parent)
    , ui(new Ui::MainWindow)
{
    ui->setupUi(this);

    setupSidebarNav();
    setupTable();
    refreshTable(QString());

    connect(ui->btnAdd, &QPushButton::clicked, this, &MainWindow::onAddClicked);
    connect(ui->btnEdit, &QPushButton::clicked, this, &MainWindow::onEditClicked);
    connect(ui->btnDelete, &QPushButton::clicked, this, &MainWindow::onDeleteClicked);
    connect(ui->searchEdit, &QLineEdit::textChanged, this, &MainWindow::onSearchChanged);
    connect(ui->tableStock, &QTableWidget::cellDoubleClicked, this, &MainWindow::onTableDoubleClicked);
    connect(ui->tableStock->selectionModel(), &QItemSelectionModel::selectionChanged,
            this, &MainWindow::onSelectionChanged);
    connect(ui->btnAlerts, &QPushButton::clicked, this, &MainWindow::onAlertsClicked);
    connect(ui->btnTopUp, &QPushButton::clicked, this, &MainWindow::onTopUpClicked);
    connect(ui->btnExport, &QPushButton::clicked, this, &MainWindow::onExportClicked);
    connect(ui->btnLocations, &QPushButton::clicked, this, &MainWindow::onLocationsClicked);

    updateAlertsForIndex(-1);
}

MainWindow::~MainWindow()
{
    delete ui;
}

void MainWindow::setupTable()
{
    ui->tableStock->setSelectionBehavior(QAbstractItemView::SelectRows);
    ui->tableStock->setSelectionMode(QAbstractItemView::SingleSelection);
    ui->tableStock->setEditTriggers(QAbstractItemView::NoEditTriggers);
    ui->tableStock->setAlternatingRowColors(true);
    ui->tableStock->horizontalHeader()->setStretchLastSection(true);
    ui->tableStock->verticalHeader()->setVisible(false);
}

void MainWindow::setupSidebarNav()
{
    m_navButtons = {
        ui->navActive,
        ui->navButton_2,
        ui->navButton_3,
        ui->navButton_4,
        ui->navAccent
    };

    for (auto *button : m_navButtons) {
        if (!button) {
            continue;
        }
        button->setProperty("nav", true);
        connect(button, &QPushButton::clicked, this, [this, button]() {
            setActiveSidebarButton(button);
        });
    }

    setActiveSidebarButton(ui->navActive);
}

void MainWindow::setActiveSidebarButton(QPushButton *activeButton)
{
    for (auto *button : m_navButtons) {
        if (!button) {
            continue;
        }
        button->setProperty("active", button == activeButton);
        button->style()->unpolish(button);
        button->style()->polish(button);
        button->update();
    }
}

void MainWindow::refreshTable(const QString &filterText)
{
    const QString filter = filterText.trimmed();

    ui->tableStock->setRowCount(0);

    for (int i = 0; i < m_items.size(); ++i) {
        const StockItem &item = m_items[i];

        if (!filter.isEmpty()) {
            const QString haystack = QString("%1 %2 %3 %4 %5 %6 %7")
                    .arg(item.id,
                         item.typeProduit,
                         QString::number(item.quantite),
                         item.unite,
                         item.dateEntree.toString("dd/MM/yyyy"),
                         item.emplacement,
                         QString::number(item.seuilAlerte));
            if (!haystack.contains(filter, Qt::CaseInsensitive)) {
                continue;
            }
        }

        const int row = ui->tableStock->rowCount();
        ui->tableStock->insertRow(row);

        auto *idItem = new QTableWidgetItem(item.id);
        idItem->setData(Qt::UserRole, i);
        ui->tableStock->setItem(row, 0, idItem);
        ui->tableStock->setItem(row, 1, new QTableWidgetItem(item.typeProduit));
        ui->tableStock->setItem(row, 2, new QTableWidgetItem(QString::number(item.quantite)));
        ui->tableStock->setItem(row, 3, new QTableWidgetItem(item.unite));
        ui->tableStock->setItem(row, 4, new QTableWidgetItem(item.dateEntree.toString("dd/MM/yyyy")));
        ui->tableStock->setItem(row, 5, new QTableWidgetItem(item.emplacement));
        ui->tableStock->setItem(row, 6, new QTableWidgetItem(QString::number(item.seuilAlerte)));
    }

    updateAlertsForIndex(selectedItemIndex());
}

int MainWindow::selectedItemIndex() const
{
    const int row = ui->tableStock->currentRow();
    if (row < 0) {
        return -1;
    }
    QTableWidgetItem *item = ui->tableStock->item(row, 0);
    if (!item) {
        return -1;
    }
    return item->data(Qt::UserRole).toInt();
}

bool MainWindow::openStockDialog(StockItem &item, bool isEdit)
{
    QDialog dialog(this);
    dialog.setWindowTitle(isEdit ? tr("Modifier Stock") : tr("Ajouter Stock"));

    auto *form = new QFormLayout(&dialog);

    auto *idEdit = new QLineEdit(&dialog);
    auto *typeCombo = new QComboBox(&dialog);
    auto *quantSpin = new QSpinBox(&dialog);
    auto *unitCombo = new QComboBox(&dialog);
    auto *dateEdit = new QDateEdit(&dialog);
    auto *empEdit = new QLineEdit(&dialog);
    auto *seuilSpin = new QSpinBox(&dialog);

    typeCombo->addItems({tr("Huile"), tr("Grignons")});
    unitCombo->addItems({tr("kg"), tr("L"), tr("t")});
    quantSpin->setRange(0, 1000000);
    seuilSpin->setRange(0, 1000000);
    dateEdit->setCalendarPopup(true);

    idEdit->setText(item.id);
    typeCombo->setCurrentText(item.typeProduit.isEmpty() ? tr("Huile") : item.typeProduit);
    quantSpin->setValue(item.quantite);
    unitCombo->setCurrentText(item.unite.isEmpty() ? tr("kg") : item.unite);
    dateEdit->setDate(item.dateEntree.isValid() ? item.dateEntree : QDate::currentDate());
    empEdit->setText(item.emplacement);
    seuilSpin->setValue(item.seuilAlerte);

    form->addRow(tr("ID Stock"), idEdit);
    form->addRow(tr("Type de produit"), typeCombo);
    form->addRow(tr("Quantité disponible"), quantSpin);
    form->addRow(tr("Unité"), unitCombo);
    form->addRow(tr("Date d’entrée"), dateEdit);
    form->addRow(tr("Emplacement"), empEdit);
    form->addRow(tr("Seuil d’alerte"), seuilSpin);

    auto *buttons = new QDialogButtonBox(QDialogButtonBox::Ok | QDialogButtonBox::Cancel, &dialog);
    form->addRow(buttons);

    connect(buttons, &QDialogButtonBox::rejected, &dialog, &QDialog::reject);
    connect(buttons, &QDialogButtonBox::accepted, &dialog, [&]() {
        if (idEdit->text().trimmed().isEmpty()) {
            QMessageBox::warning(&dialog, tr("Validation"), tr("ID Stock est obligatoire."));
            return;
        }
        if (empEdit->text().trimmed().isEmpty()) {
            QMessageBox::warning(&dialog, tr("Validation"), tr("Emplacement est obligatoire."));
            return;
        }
        dialog.accept();
    });

    if (dialog.exec() != QDialog::Accepted) {
        return false;
    }

    item.id = idEdit->text().trimmed();
    item.typeProduit = typeCombo->currentText();
    item.quantite = quantSpin->value();
    item.unite = unitCombo->currentText();
    item.dateEntree = dateEdit->date();
    item.emplacement = empEdit->text().trimmed();
    item.seuilAlerte = seuilSpin->value();

    return true;
}

void MainWindow::onAddClicked()
{
    StockItem item;
    item.dateEntree = QDate::currentDate();

    if (openStockDialog(item, false)) {
        m_items.append(item);
        refreshTable(ui->searchEdit->text());
    }
}

void MainWindow::onEditClicked()
{
    const int index = selectedItemIndex();
    if (index < 0 || index >= m_items.size()) {
        QMessageBox::information(this, tr("Modifier"), tr("Sélectionnez une ligne à modifier."));
        return;
    }

    StockItem item = m_items[index];
    if (openStockDialog(item, true)) {
        m_items[index] = item;
        refreshTable(ui->searchEdit->text());
    }
}

void MainWindow::onDeleteClicked()
{
    const int index = selectedItemIndex();
    if (index < 0 || index >= m_items.size()) {
        QMessageBox::information(this, tr("Supprimer"), tr("Sélectionnez une ligne à supprimer."));
        return;
    }

    const auto reply = QMessageBox::question(
                this,
                tr("Supprimer"),
                tr("Voulez-vous supprimer cet élément ?"));

    if (reply == QMessageBox::Yes) {
        m_items.removeAt(index);
        refreshTable(ui->searchEdit->text());
    }
}

void MainWindow::onSearchChanged(const QString &text)
{
    refreshTable(text);
}

void MainWindow::onTableDoubleClicked()
{
    onEditClicked();
}

void MainWindow::onSelectionChanged()
{
    updateAlertsForIndex(selectedItemIndex());
}

void MainWindow::onAlertsClicked()
{
    showAlertsDialog();
}

void MainWindow::onTopUpClicked()
{
    showTopUpDialog();
}

void MainWindow::onExportClicked()
{
    exportCsv();
}

void MainWindow::onLocationsClicked()
{
    showLocationsDialog();
}

void MainWindow::updateAlertsForIndex(int index)
{
    if (index < 0 || index >= m_items.size()) {
        ui->alertLow->setText(tr("Stock faible (par défaut)"));
        ui->alertLow->setVisible(true);
        ui->alertMid->setVisible(false);
        ui->alertOk->setVisible(false);
        return;
    }

    const StockItem &item = m_items[index];
    const int seuil = item.seuilAlerte;
    const int quantite = item.quantite;

    if (seuil <= 0) {
        ui->alertOk->setText(tr("Niveau optimal (%1 %2)").arg(quantite).arg(item.unite));
        ui->alertLow->setVisible(false);
        ui->alertMid->setVisible(false);
        ui->alertOk->setVisible(true);
        return;
    }

    const int midThreshold = seuil + qMax(1, seuil / 2);

    if (quantite <= seuil) {
        ui->alertLow->setText(tr("Stock faible (%1 %2 / seuil %3)")
                                  .arg(quantite)
                                  .arg(item.unite)
                                  .arg(seuil));
        ui->alertLow->setVisible(true);
        ui->alertMid->setVisible(false);
        ui->alertOk->setVisible(false);
    } else if (quantite <= midThreshold) {
        ui->alertMid->setText(tr("Seuil moyen (%1 %2 / seuil %3)")
                                  .arg(quantite)
                                  .arg(item.unite)
                                  .arg(seuil));
        ui->alertLow->setVisible(false);
        ui->alertMid->setVisible(true);
        ui->alertOk->setVisible(false);
    } else {
        ui->alertOk->setText(tr("Niveau optimal (%1 %2 / seuil %3)")
                                 .arg(quantite)
                                 .arg(item.unite)
                                 .arg(seuil));
        ui->alertLow->setVisible(false);
        ui->alertMid->setVisible(false);
        ui->alertOk->setVisible(true);
    }
}

void MainWindow::showAlertsDialog()
{
    QStringList lines;
    for (const auto &item : m_items) {
        if (item.seuilAlerte > 0 && item.quantite <= item.seuilAlerte) {
            lines << tr("%1 - %2 (%3 %4, seuil %5)")
                         .arg(item.id, item.typeProduit)
                         .arg(item.quantite)
                         .arg(item.unite)
                         .arg(item.seuilAlerte);
        }
    }

    if (lines.isEmpty()) {
        QMessageBox::information(this, tr("Alertes stock"), tr("Aucune alerte critique."));
        return;
    }

    QMessageBox::warning(this, tr("Alertes stock"), lines.join('\n'));
}

void MainWindow::showTopUpDialog()
{
    QStringList lines;
    for (const auto &item : m_items) {
        if (item.seuilAlerte <= 0) {
            continue;
        }
        if (item.quantite <= item.seuilAlerte) {
            const int target = item.seuilAlerte * 2;
            const int toOrder = qMax(0, target - item.quantite);
            lines << tr("%1 - %2: recommander %3 %4")
                         .arg(item.id, item.typeProduit)
                         .arg(toOrder)
                         .arg(item.unite);
        }
    }

    if (lines.isEmpty()) {
        QMessageBox::information(this, tr("Top-Up"), tr("Aucune recommandation."));
        return;
    }

    QMessageBox::information(this, tr("Top-Up Recommendations"), lines.join('\n'));
}

void MainWindow::exportCsv()
{
    const QString path = QFileDialog::getSaveFileName(
                this,
                tr("Exporter rapports"),
                QString(),
                tr("CSV (*.csv)"));

    if (path.isEmpty()) {
        return;
    }

    QFile file(path);
    if (!file.open(QIODevice::WriteOnly | QIODevice::Text)) {
        QMessageBox::warning(this, tr("Exporter rapports"), tr("Impossible d'écrire le fichier."));
        return;
    }

    QTextStream out(&file);
    out << "ID Stock,Type de produit,Quantite disponible,Unite,Date d'entree,Emplacement,Seuil d'alerte\n";

    for (int row = 0; row < ui->tableStock->rowCount(); ++row) {
        QStringList rowValues;
        for (int col = 0; col < ui->tableStock->columnCount(); ++col) {
            QTableWidgetItem *cell = ui->tableStock->item(row, col);
            rowValues << (cell ? cell->text() : QString());
        }
        out << rowValues.join(',') << "\n";
    }

    file.close();
    QMessageBox::information(this, tr("Exporter rapports"), tr("Export terminé."));
}

void MainWindow::showLocationsDialog()
{
    QMessageBox::information(this, tr("Emplacements"),
                             tr("Gestion des emplacements: fonctionnalité à connecter."));
}
