#include "mainwindow.h"
#include "ui_mainwindow.h"
#include <QVBoxLayout>
#include <QGroupBox>
#include <QMessageBox>
#include <QApplication>
#include <QDateTime>
#include <QFileDialog>
#include <QTextDocument>
#include <QPdfWriter>
#include <QTableWidget>
#include <QMap>
#include <algorithm>

MainWindow::MainWindow(QWidget *parent)
    : QMainWindow(parent)
    , ui(new Ui::MainWindow)
{
    ui->setupUi(this);
    setWindowTitle("Smart Oil Press - Gestion des Agriculteurs");
    setGeometry(100, 100, 1600, 900);
    
    setupStyleSheet();
    setupConnections();
    chargerDonnees();
}

MainWindow::~MainWindow()
{
    delete ui;
}

void MainWindow::setupStyleSheet()
{
    // Appliquer le style Fusion
    QApplication::setStyle("Fusion");
    
    // StyleSheet professionnel et moderne
    QString styleSheet = R"(
        QMainWindow, QWidget {
            background-color: #F5F5F5;
        }
        
        QGroupBox {
            border: 2px solid #556B2F;
            border-radius: 8px;
            margin-top: 18px;
            padding-top: 15px;
            padding-left: 10px;
            padding-right: 10px;
            padding-bottom: 10px;
            font-weight: bold;
            color: #000000;
            background-color: #FFFFFF;
        }
        
        QGroupBox::title {
            subcontrol-origin: margin;
            left: 15px;
            padding: 0 5px 0 5px;
            color: #000000;
            font-size: 12px;
            font-weight: bold;
        }
        
        QLabel {
            color: #000000;
            font-weight: bold;
            font-size: 11px;
        }
        
        QLineEdit {
            border: 1px solid #D0D0D0;
            border-radius: 4px;
            padding: 8px;
            background-color: white;
            color: #333;
            font-size: 11px;
            selection-background-color: #FBC02D;
        }
        
        QLineEdit:focus {
            border: 2px solid #556B2F;
            background-color: #FFFEF5;
            outline: none;
        }
        
        QLineEdit:read-only {
            background-color: #EFEFEF;
            color: #999;
            border: 1px solid #DDD;
        }
        
        QPushButton {
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 12px;
            color: white;
        }
        
        #ajouterBtn {
            background-color: #556B2F;
        }
        
        #ajouterBtn:hover {
            background-color: #3D5C1F;
        }
        
        #ajouterBtn:pressed {
            background-color: #0D3D0D;
        }
        
        #modifierBtn {
            background-color: #556B2F;
        }
        
        #modifierBtn:hover {
            background-color: #3D5C1F;
        }
        
        #modifierBtn:pressed {
            background-color: #0D3D0D;
        }
        
        #effacerBtn {
            background-color: #FBC02D;
            color: #1F4788;
        }
        
        #effacerBtn:hover {
            background-color: #F9A825;
            color: #1F4788;
        }
        
        #effacerBtn:pressed {
            background-color: #E89E1B;
            color: #1F4788;
        }
        
        #supprimerBtn {
            background-color: #C62828;
            color: white;
        }
        
        #supprimerBtn:hover {
            background-color: #B71C1C;
            color: white;
        }
        
        #supprimerBtn:pressed {
            background-color: #8B0000;
        }
        
        #rechercherBtn {
            background-color: #1F4788;
            color: white;
        }
        
        #rechercherBtn:hover {
            background-color: #142E54;
        }
        
        #rechercherBtn:pressed {
            background-color: #0A1F3A;
        }
        
        #culturesBtn {
            background-color: #4CAF50;
            color: white;
        }
        
        #culturesBtn:hover {
            background-color: #45a049;
        }
        
        #culturesBtn:pressed {
            background-color: #3d8b40;
        }
        
        #historiqueBtn {
            background-color: #2196F3;
            color: white;
        }
        
        #historiqueBtn:hover {
            background-color: #0b7dda;
        }
        
        #historiqueBtn:pressed {
            background-color: #0056b3;
        }
        
        #statsFormBtn {
            background-color: #FF9800;
            color: white;
        }
        
        #statsFormBtn:hover {
            background-color: #e68900;
        }
        
        #statsFormBtn:pressed {
            background-color: #cc7a00;
        }
        
        #exportBtn {
            background-color: #9C27B0;
            color: white;
        }
        
        #exportBtn:hover {
            background-color: #8a1f8f;
        }
        
        #exportBtn:pressed {
            background-color: #681a6f;
        }
        
        QTableWidget {
            border: 1px solid #556B2F;
            border-radius: 5px;
            background-color: white;
            gridline-color: #E8E8E8;
            color: #333;
        }
        
        QTableWidget::item {
            padding: 6px;
            border: none;
            color: #333;
        }
        
        QTableWidget::item:selected {
            background-color: #FBC02D;
            color: #000000;
            font-weight: bold;
        }
        
        QTableWidget::item:alternate {
            background-color: #FAFAFA;
        }
        
        QTableWidget::item:alternate:selected {
            background-color: #FBC02D;
            color: #000000;
        }
        
        QHeaderView::section {
            background-color: #1F4788;
            color: white;
            padding: 8px;
            border: none;
            font-weight: bold;
            font-size: 11px;
        }
        
        QScrollBar:vertical {
            background-color: #F5F5F5;
            width: 14px;
            border: 1px solid #DDD;
        }
        
        QScrollBar::handle:vertical {
            background-color: #556B2F;
            border-radius: 7px;
            min-height: 20px;
        }
        
        QScrollBar::handle:vertical:hover {
            background-color: #3D5C1F;
        }
        
        QScrollBar::up-arrow:vertical {
            image: none;
        }
        
        QScrollBar::down-arrow:vertical {
            image: none;
        }
        
        /* Sidebar buttons */
        #agriBtn {
            background-color: #556B2F;
            color: white;
            border: none;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        
        #agriBtn:hover {
            background-color: #3D5C1F;
        }
        
        #statsBtn, #settingsBtn {
            background-color: transparent;
            color: white;
            border: none;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        
        #statsBtn:hover, #settingsBtn:hover {
            background-color: #556B2F;
        }
        
        #logoutBtn {
            background-color: transparent;
            color: #FF6B6B;
            border: none;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        
        #logoutBtn:hover {
            background-color: #C62828;
            color: white;
        }
        
        /* Top bar styling */
        #topBar {
            background-color: #556B2F;
            padding: 0px;
        }
        
        #sidebar {
            background-color: #3D5C1F;
            padding: 0px;
        }
    )";
    
    this->setStyleSheet(styleSheet);
}

void MainWindow::setupConnections()
{
    // Boutons de formulaire
    connect(ui->ajouterBtn, &QPushButton::clicked, this, &MainWindow::onAjouter);
    connect(ui->modifierBtn, &QPushButton::clicked, this, &MainWindow::onModifier);
    connect(ui->supprimerBtn, &QPushButton::clicked, this, &MainWindow::onSupprimer);
    connect(ui->effacerBtn, &QPushButton::clicked, this, &MainWindow::onClearForm);
    connect(ui->rechercherBtn, &QPushButton::clicked, this, &MainWindow::onRechercher);
    connect(ui->tableWidget, &QTableWidget::itemSelectionChanged, this, &MainWindow::onTableSelectionChanged);
    
    // Boutons avancés
    connect(ui->culturesBtn, &QPushButton::clicked, this, &MainWindow::onAfficherCultures);
    connect(ui->historiqueBtn, &QPushButton::clicked, this, &MainWindow::onAfficherHistorique);
    connect(ui->statsFormBtn, &QPushButton::clicked, this, &MainWindow::onAfficherStats);
    connect(ui->exportBtn, &QPushButton::clicked, this, &MainWindow::onExportPDF);
}

void MainWindow::chargerDonnees()
{
    // Données d'exemple agriculteurs
    agriculteurs.append(Agriculteur(1, "AB123456", "Ahmed Mohamed", "Sfax", "98123456", "ahmed@email.com", "50", "1000"));
    agriculteurs.append(Agriculteur(2, "CD789012", "Fatima Ali", "Sousse", "98234567", "fatima@email.com", "75", "1500"));
    agriculteurs.append(Agriculteur(3, "EF345678", "Youssef Hassan", "Kairouan", "98345678", "youssef@email.com", "120", "2500"));
    
    // Données d'exemple cultures
    cultures.append(Culture(1, 1, "Oliviers Chemlali", "Olives", "30", QDate(2020, 4, 15), "Chemlali", "Productif"));
    cultures.append(Culture(2, 1, "Oliviers Koroneiki", "Olives", "20", QDate(2021, 5, 10), "Koroneiki", "En développement"));
    cultures.append(Culture(3, 2, "Oliviers Manzanillo", "Olives", "50", QDate(2019, 3, 20), "Manzanillo", "Productif"));
    cultures.append(Culture(4, 3, "Oliviers Frantoio", "Olives", "70", QDate(2018, 4, 5), "Frantoio", "Productif"));
    cultures.append(Culture(5, 3, "Oliviers Arbequina", "Olives", "50", QDate(2021, 6, 1), "Arbequina", "En développement"));
    
    // Données d'exemple historique
    historique.append(Historique(1, 1, "CREATION", "Agriculteur créé", QDateTime(QDate(2023, 1, 15), QTime(10, 30)), "Admin"));
    historique.append(Historique(2, 1, "MODIFICATION", "Mise à jour téléphone", QDateTime(QDate(2023, 6, 20), QTime(14, 45)), "Admin"));
    historique.append(Historique(3, 2, "CREATION", "Agriculteur créé", QDateTime(QDate(2023, 2, 10), QTime(11, 0)), "Admin"));
    
    // Configure table columns
    QStringList headers;
    headers << "ID" << "CIN" << "Nom" << "Adresse" << "Téléphone" << "Email" << "Superficie (ha)" << "Nombre d'Oliviers";
    ui->tableWidget->setHorizontalHeaderLabels(headers);
    ui->tableWidget->setColumnCount(8);
    ui->tableWidget->setAlternatingRowColors(true);
    ui->tableWidget->resizeColumnsToContents();
    
    afficherTableau();
}

void MainWindow::afficherTableau()
{
    ui->tableWidget->setRowCount(0);
    
    for (int i = 0; i < agriculteurs.size(); ++i) {
        const Agriculteur &ag = agriculteurs[i];
        ui->tableWidget->insertRow(i);
        
        ui->tableWidget->setItem(i, 0, new QTableWidgetItem(QString::number(ag.getId())));
        ui->tableWidget->setItem(i, 1, new QTableWidgetItem(ag.getCin()));
        ui->tableWidget->setItem(i, 2, new QTableWidgetItem(ag.getNom()));
        ui->tableWidget->setItem(i, 3, new QTableWidgetItem(ag.getAdresse()));
        ui->tableWidget->setItem(i, 4, new QTableWidgetItem(ag.getTelephone()));
        ui->tableWidget->setItem(i, 5, new QTableWidgetItem(ag.getEmail()));
        ui->tableWidget->setItem(i, 6, new QTableWidgetItem(ag.getSuperficie()));
        ui->tableWidget->setItem(i, 7, new QTableWidgetItem(ag.getOliviers()));
    }
}

void MainWindow::afficherAgriculteurdansForm(const Agriculteur &ag)
{
    ui->idEdit->setText(QString::number(ag.getId()));
    ui->cinEdit->setText(ag.getCin());
    ui->nomEdit->setText(ag.getNom());
    ui->adresseEdit->setText(ag.getAdresse());
    ui->telephoneEdit->setText(ag.getTelephone());
    ui->emailEdit->setText(ag.getEmail());
    ui->superficieEdit->setText(ag.getSuperficie());
    ui->oliviersEdit->setText(ag.getOliviers());
}

void MainWindow::onAjouter()
{
    if (ui->cinEdit->text().isEmpty() || ui->nomEdit->text().isEmpty()) {
        QMessageBox::warning(this, "Erreur", "Veuillez remplir au moins CIN et Nom!");
        return;
    }
    
    int newId = agriculteurs.isEmpty() ? 1 : agriculteurs.last().getId() + 1;
    Agriculteur newAg(newId, ui->cinEdit->text(), ui->nomEdit->text(), ui->adresseEdit->text(),
                      ui->telephoneEdit->text(), ui->emailEdit->text(), ui->superficieEdit->text(),
                      ui->oliviersEdit->text());
    
    agriculteurs.append(newAg);
    ajouterHistorique(newId, "CREATION", "Agriculteur: " + newAg.getNom());
    afficherTableau();
    onClearForm();
    QMessageBox::information(this, "Succès", "Agriculteur ajouté avec succès!");
}

void MainWindow::onModifier()
{
    if (selectedIndex == -1) {
        QMessageBox::warning(this, "Erreur", "Veuillez sélectionner un agriculteur à modifier!");
        return;
    }
    
    int agricId = agriculteurs[selectedIndex].getId();
    agriculteurs[selectedIndex].setCin(ui->cinEdit->text());
    agriculteurs[selectedIndex].setNom(ui->nomEdit->text());
    agriculteurs[selectedIndex].setAdresse(ui->adresseEdit->text());
    agriculteurs[selectedIndex].setTelephone(ui->telephoneEdit->text());
    agriculteurs[selectedIndex].setEmail(ui->emailEdit->text());
    agriculteurs[selectedIndex].setSuperficie(ui->superficieEdit->text());
    agriculteurs[selectedIndex].setOliviers(ui->oliviersEdit->text());
    
    ajouterHistorique(agricId, "MODIFICATION", "Mise à jour: " + ui->nomEdit->text());
    afficherTableau();
    onClearForm();
    QMessageBox::information(this, "Succès", "Agriculteur modifié avec succès!");
}

void MainWindow::onSupprimer()
{
    if (selectedIndex == -1) {
        QMessageBox::warning(this, "Erreur", "Veuillez sélectionner un agriculteur à supprimer!");
        return;
    }
    
    QMessageBox::StandardButton reply = QMessageBox::question(this, "Confirmation",
        "Êtes-vous sûr de vouloir supprimer cet agriculteur?",
        QMessageBox::Yes | QMessageBox::No);
    
    if (reply == QMessageBox::Yes) {
        int agricId = agriculteurs[selectedIndex].getId();
        QString nomAg = agriculteurs[selectedIndex].getNom();
        ajouterHistorique(agricId, "SUPPRESSION", "Agriculteur supprimé: " + nomAg);
        agriculteurs.removeAt(selectedIndex);
        afficherTableau();
        onClearForm();
        QMessageBox::information(this, "Succès", "Agriculteur supprimé avec succès!");
    }
}

void MainWindow::onRechercher()
{
    QString searchText = ui->searchEdit->text().toLower();
    
    if (searchText.isEmpty()) {
        afficherTableau();
        return;
    }
    
    ui->tableWidget->setRowCount(0);
    
    int rowCount = 0;
    for (int i = 0; i < agriculteurs.size(); ++i) {
        const Agriculteur &ag = agriculteurs[i];
        if (ag.getNom().toLower().contains(searchText) || ag.getCin().toLower().contains(searchText)) {
            ui->tableWidget->insertRow(rowCount);
            
            ui->tableWidget->setItem(rowCount, 0, new QTableWidgetItem(QString::number(ag.getId())));
            ui->tableWidget->setItem(rowCount, 1, new QTableWidgetItem(ag.getCin()));
            ui->tableWidget->setItem(rowCount, 2, new QTableWidgetItem(ag.getNom()));
            ui->tableWidget->setItem(rowCount, 3, new QTableWidgetItem(ag.getAdresse()));
            ui->tableWidget->setItem(rowCount, 4, new QTableWidgetItem(ag.getTelephone()));
            ui->tableWidget->setItem(rowCount, 5, new QTableWidgetItem(ag.getEmail()));
            ui->tableWidget->setItem(rowCount, 6, new QTableWidgetItem(ag.getSuperficie()));
            ui->tableWidget->setItem(rowCount, 7, new QTableWidgetItem(ag.getOliviers()));
            
            rowCount++;
        }
    }
}

void MainWindow::onTableSelectionChanged()
{
    if (ui->tableWidget->selectedItems().isEmpty()) {
        selectedIndex = -1;
        return;
    }
    
    int row = ui->tableWidget->row(ui->tableWidget->selectedItems()[0]);
    
    // Trouver l'index réel dans la liste
    QString cin = ui->tableWidget->item(row, 1)->text();
    for (int i = 0; i < agriculteurs.size(); ++i) {
        if (agriculteurs[i].getCin() == cin) {
            selectedIndex = i;
            afficherAgriculteurdansForm(agriculteurs[i]);
            return;
        }
    }
}

void MainWindow::onClearForm()
{
    ui->idEdit->clear();
    ui->cinEdit->clear();
    ui->nomEdit->clear();
    ui->adresseEdit->clear();
    ui->telephoneEdit->clear();
    ui->emailEdit->clear();
    ui->superficieEdit->clear();
    ui->oliviersEdit->clear();
    ui->searchEdit->clear();
    
    selectedIndex = -1;
    
    ui->tableWidget->clearSelection();
}

void MainWindow::onAfficherCultures()
{
    if (selectedIndex < 0) {
        QMessageBox::warning(this, "Aucune sélection", "Veuillez sélectionner un agriculteur");
        return;
    }
    
    const Agriculteur &ag = agriculteurs[selectedIndex];
    
    QString msg = QString("Cultures de : %1\n\n").arg(ag.getNom());
    
    bool found = false;
    for (const Culture &c : cultures) {
        if (c.getAgriculteurId() == ag.getId()) {
            found = true;
            msg += QString("- %1 (%2)\n  Type: %3 | Surface: %4 ha\n  Variété: %5 | État: %6\n")
                .arg(c.getNom(), c.getType(), c.getSuperficie(), 
                     c.getVariete(), c.getEtat());
        }
    }
    
    if (!found) {
        msg += "Aucune culture enregistrée";
    }
    
    QMessageBox::information(this, "Cultures", msg);
}

void MainWindow::onAfficherHistorique()
{
    if (selectedIndex < 0) {
        QMessageBox::warning(this, "Aucune sélection", "Veuillez sélectionner un agriculteur");
        return;
    }
    
    const Agriculteur &ag = agriculteurs[selectedIndex];
    
    QString msg = QString("Historique : %1\n\n").arg(ag.getNom());
    
    bool found = false;
    for (const Historique &h : historique) {
        if (h.getAgriculteurId() == ag.getId()) {
            found = true;
            msg += QString("[%1] %2\n  Action: %3\n  Détails: %4\n\n")
                .arg(h.getDateHeure().toString("dd/MM/yyyy hh:mm:ss"),
                     h.getUtilisateur(), h.getAction(), h.getDetails());
        }
    }
    
    if (!found) {
        msg += "Aucun historique";
    }
    
    QMessageBox::information(this, "Historique", msg);
}

void MainWindow::ajouterHistorique(int agriculteurId, const QString &action, const QString &details)
{
    static int hId = 1;
    Historique h(hId++, agriculteurId, action, details, 
                 QDateTime::currentDateTime(), "Admin");
    historique.append(h);
}

void MainWindow::onAfficherStats()
{
    afficherStatistiques();
}

void MainWindow::afficherStatistiques()
{
    QString stats;
    
    // Statistiques générales
    stats += QString("📊 STATISTIQUES GÉNÉRALES\n\n");
    stats += QString("Nombre total d'agriculteurs: %1\n").arg(agriculteurs.size());
    stats += QString("Nombre total de cultures: %1\n").arg(cultures.size());
    
    // Surface totale
    double superficieTotale = 0;
    int oliviersTotaux = 0;
    
    for (const Agriculteur &ag : agriculteurs) {
        superficieTotale += ag.getSuperficie().toDouble();
        oliviersTotaux += ag.getOliviers().toInt();
    }
    
    stats += QString("\nSuperficie totale: %1 hectares\n").arg(superficieTotale, 0, 'f', 2);
    stats += QString("Nombre total d'oliviers: %1\n\n").arg(oliviersTotaux);
    
    // Cultures par type
    stats += "Cultures par type:\n";
    QMap<QString, int> culturesParType;
    for (const Culture &c : cultures) {
        culturesParType[c.getType()]++;
    }
    
    for (const auto &type : culturesParType.keys()) {
        stats += QString("- %1: %2 parcelles\n").arg(type).arg(culturesParType[type]);
    }
    
    // Agriculteur avec plus de cultures
    if (!agriculteurs.isEmpty()) {
        stats += "\nTop agriculteurs (par nombre de cultures):\n";
        QMap<int, int> culturesParAgriculteur;
        
        for (const Culture &c : cultures) {
            culturesParAgriculteur[c.getAgriculteurId()]++;
        }
        
        int count = 0;
        for (auto it = culturesParAgriculteur.begin(); it != culturesParAgriculteur.end() && count < 3; ++it) {
            for (const Agriculteur &ag : agriculteurs) {
                if (ag.getId() == it.key()) {
                    stats += QString("- %1: %2 cultures\n").arg(ag.getNom()).arg(it.value());
                    count++;
                    break;
                }
            }
        }
    }
    
    QMessageBox::information(this, "Statistiques", stats);
}

void MainWindow::onExportPDF()
{
    QString fileName = QFileDialog::getSaveFileName(this, "Exporter en PDF", 
        "", "PDF Files (*.pdf)");
    
    if (fileName.isEmpty()) return;
    
    if (!fileName.endsWith(".pdf", Qt::CaseInsensitive)) {
        fileName += ".pdf";
    }
    
    exporterEnPDF();
    // Note: implémentation simplifiée
    QMessageBox::information(this, "Export", "PDF exporté: " + fileName);
}

void MainWindow::exporterEnPDF()
{
    // À implémenter avec QPdfWriter
}

void MainWindow::onExportExcel()
{
    QString fileName = QFileDialog::getSaveFileName(this, "Exporter en Excel",
        "", "Excel Files (*.csv)");
    
    if (fileName.isEmpty()) return;
    
    if (!fileName.endsWith(".csv", Qt::CaseInsensitive)) {
        fileName += ".csv";
    }
    
    exporterEnExcel();
}

void MainWindow::exporterEnExcel()
{
    // À implémenter avec génération CSV
}

