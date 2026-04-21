#include "mainwindow.h"
#include "ui_mainwindow.h"
#include "connection.h"
#include "statsdialog.h"

#include <QVBoxLayout>
#include <QGroupBox>
#include <QMessageBox>
#include <QApplication>
#include <QDateTime>
#include <QFileDialog>
#include <QTextDocument>
#include <QPdfWriter>
#include <QPainter>
#include <QTableWidget>
#include <QHeaderView>
#include <QMap>
#include <QSqlQuery>
#include <QSqlError>
#include <QFile>
#include <QFileInfo>
#include <QTextStream>
#include <QDebug>
#include <QSettings>
#include <QDir>
#include <QDesktopServices>
#include <QUrl>
#include <QCoreApplication>
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
    // Le style est défini dans mainwindow.ui
    QApplication::setStyle("Fusion");
}

void MainWindow::setupConnections()
{
    // Formulaire
    connect(ui->ajouterBtn,  &QPushButton::clicked, this, &MainWindow::onAjouter);
    connect(ui->modifierBtn, &QPushButton::clicked, this, &MainWindow::onModifier);
    connect(ui->supprimerBtn,&QPushButton::clicked, this, &MainWindow::onSupprimer);
    connect(ui->effacerBtn,  &QPushButton::clicked, this, &MainWindow::onClearForm);

    // Recherche en temps réel depuis la barre du haut
    connect(ui->searchInput, &QLineEdit::textChanged, this, &MainWindow::onRechercher);

    // Bouton "Nouveau" dans la barre du haut → vide le formulaire
    connect(ui->btnAdd, &QPushButton::clicked, this, &MainWindow::onNouveauAgriculteur);

    // Table
    connect(ui->tableWidget, &QTableWidget::itemSelectionChanged,
            this, &MainWindow::onTableSelectionChanged);

    // Boutons avancés
    connect(ui->historiqueBtn,&QPushButton::clicked, this, &MainWindow::onAfficherHistorique);
    connect(ui->statsFormBtn,       &QPushButton::clicked, this, &MainWindow::onAfficherStatsOliviers);
    connect(ui->statsSuperficieBtn, &QPushButton::clicked, this, &MainWindow::onAfficherStatsSuperficie);
    connect(ui->exportBtn,        &QPushButton::clicked, this, &MainWindow::onExportPDF);
    connect(ui->pieceJointeBtn,   &QPushButton::clicked, this, &MainWindow::onJoindreFichier);
    connect(ui->ouvrirFichierBtn, &QPushButton::clicked, this, &MainWindow::onOuvrirFichier);
}

// ============================================================
// 📊 CHARGEMENT DES DONNÉES DEPUIS ORACLE
// ============================================================
void MainWindow::chargerDonnees()
{
    QSqlDatabase db = Connection::createInstance().getDB();

    // ---------- Agriculteurs ----------
    agriculteurs.clear();
    QSqlQuery qa(db);
    if (qa.exec("SELECT ID, CIN, NOM, ADRESSE, TELEPHONE, EMAIL, SUPERFICIE, OLIVIERS "
                "FROM AGRICULTEUR ORDER BY ID")) {
        while (qa.next()) {
            agriculteurs.append(Agriculteur(
                qa.value("ID").toInt(),
                qa.value("CIN").toString(),
                qa.value("NOM").toString(),
                qa.value("ADRESSE").toString(),
                qa.value("TELEPHONE").toString(),
                qa.value("EMAIL").toString(),
                qa.value("SUPERFICIE").toString(),
                qa.value("OLIVIERS").toString()
                ));
        }
    } else {
        QMessageBox::critical(this, "Erreur SQL (Agriculteur)", qa.lastError().text());
    }

    // ---------- Cultures (optionnel : si la table existe) ----------
    cultures.clear();
    QSqlQuery qc(db);
    if (qc.exec("SELECT ID, AGRICULTEUR_ID, NOM, TYPE, SUPERFICIE, "
                "DATE_PLANTATION, VARIETE, ETAT FROM CULTURE ORDER BY ID")) {
        while (qc.next()) {
            cultures.append(Culture(
                qc.value("ID").toInt(),
                qc.value("AGRICULTEUR_ID").toInt(),
                qc.value("NOM").toString(),
                qc.value("TYPE").toString(),
                qc.value("SUPERFICIE").toString(),
                qc.value("DATE_PLANTATION").toDate(),
                qc.value("VARIETE").toString(),
                qc.value("ETAT").toString()
                ));
        }
    } else {
        qDebug() << "Table CULTURE non disponible :" << qc.lastError().text();
    }

    // ---------- Historique (optionnel : si la table existe) ----------
    historique.clear();
    QSqlQuery qh(db);
    if (qh.exec("SELECT ID, AGRICULTEUR_ID, ACTION, DETAILS, DATE_HEURE, UTILISATEUR "
                "FROM HISTORIQUE ORDER BY DATE_HEURE DESC")) {
        while (qh.next()) {
            historique.append(Historique(
                qh.value("ID").toInt(),
                qh.value("AGRICULTEUR_ID").toInt(),
                qh.value("ACTION").toString(),
                qh.value("DETAILS").toString(),
                qh.value("DATE_HEURE").toDateTime(),
                qh.value("UTILISATEUR").toString()
                ));
        }
    } else {
        qDebug() << "Table HISTORIQUE non disponible :" << qh.lastError().text();
    }

    // Configuration du tableau
    QStringList headers;
    headers << "ID" << "CIN" << "Nom" << "Adresse"
            << "Téléphone" << "Email" << "Superficie (ha)" << "Nb Oliviers";
    ui->tableWidget->setColumnCount(8);
    ui->tableWidget->setHorizontalHeaderLabels(headers);
    ui->tableWidget->setEditTriggers(QAbstractItemView::NoEditTriggers);
    ui->tableWidget->verticalHeader()->setVisible(false);
    ui->tableWidget->horizontalHeader()->setStretchLastSection(true);
    ui->tableWidget->setAlternatingRowColors(true);

    afficherTableau();
    updateKPIs();
}

void MainWindow::updateKPIs()
{
    ui->kpiTotalValue->setText(QString::number(agriculteurs.size()));

    double superficieTotale = 0;
    int oliviersTotaux = 0;
    for (const Agriculteur &ag : agriculteurs) {
        superficieTotale += ag.getSuperficie().toDouble();
        oliviersTotaux   += ag.getOliviers().toInt();
    }
    ui->kpiSuperficieValue->setText(QString("%1 ha").arg(superficieTotale, 0, 'f', 1));
    ui->kpiOliviersValue->setText(QString::number(oliviersTotaux));
    ui->kpiCulturesValue->setText(QString::number(cultures.size()));
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

// ============================================================
// ➕ AJOUTER (Oracle INSERT)
// ============================================================
void MainWindow::onAjouter()
{
    if (ui->cinEdit->text().isEmpty() || ui->nomEdit->text().isEmpty()) {
        QMessageBox::warning(this, "Erreur", "Veuillez remplir au moins CIN et Nom!");
        return;
    }

    QSqlQuery query(Connection::createInstance().getDB());
    query.prepare(
        "INSERT INTO AGRICULTEUR (CIN, NOM, ADRESSE, TELEPHONE, EMAIL, SUPERFICIE, OLIVIERS) "
        "VALUES (:cin, :nom, :adresse, :telephone, :email, :superficie, :oliviers)"
        );
    query.bindValue(":cin",        ui->cinEdit->text());
    query.bindValue(":nom",        ui->nomEdit->text());
    query.bindValue(":adresse",    ui->adresseEdit->text());
    query.bindValue(":telephone",  ui->telephoneEdit->text());
    query.bindValue(":email",      ui->emailEdit->text());
    query.bindValue(":superficie", ui->superficieEdit->text());
    query.bindValue(":oliviers",   ui->oliviersEdit->text());

    if (query.exec()) {
        // Recharger les données depuis la base
        chargerDonnees();
        ajouterHistorique(0, "CREATION", "Agriculteur: " + ui->nomEdit->text());
        onClearForm();
        QMessageBox::information(this, "Succès", "Agriculteur ajouté avec succès!");
    } else {
        QMessageBox::critical(this, "Erreur SQL", query.lastError().text());
    }
}

// ============================================================
// ✏️ MODIFIER (Oracle UPDATE)
// ============================================================
void MainWindow::onModifier()
{
    if (selectedIndex == -1) {
        QMessageBox::warning(this, "Erreur", "Veuillez sélectionner un agriculteur à modifier!");
        return;
    }

    int id = agriculteurs[selectedIndex].getId();

    QSqlQuery query(Connection::createInstance().getDB());
    query.prepare(
        "UPDATE AGRICULTEUR SET CIN=:cin, NOM=:nom, ADRESSE=:adresse, "
        "TELEPHONE=:telephone, EMAIL=:email, SUPERFICIE=:superficie, "
        "OLIVIERS=:oliviers WHERE ID=:id"
        );
    query.bindValue(":cin",        ui->cinEdit->text());
    query.bindValue(":nom",        ui->nomEdit->text());
    query.bindValue(":adresse",    ui->adresseEdit->text());
    query.bindValue(":telephone",  ui->telephoneEdit->text());
    query.bindValue(":email",      ui->emailEdit->text());
    query.bindValue(":superficie", ui->superficieEdit->text());
    query.bindValue(":oliviers",   ui->oliviersEdit->text());
    query.bindValue(":id",         id);

    if (query.exec()) {
        chargerDonnees();
        ajouterHistorique(id, "MODIFICATION", "Mise à jour: " + ui->nomEdit->text());
        onClearForm();
        QMessageBox::information(this, "Succès", "Agriculteur modifié avec succès!");
    } else {
        QMessageBox::critical(this, "Erreur SQL", query.lastError().text());
    }
}

// ============================================================
// 🗑️ SUPPRIMER (Oracle DELETE)
// ============================================================
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
        int id = agriculteurs[selectedIndex].getId();
        QString nomAg = agriculteurs[selectedIndex].getNom();

        QSqlQuery query(Connection::createInstance().getDB());
        query.prepare("DELETE FROM AGRICULTEUR WHERE ID=:id");
        query.bindValue(":id", id);

        if (query.exec()) {
            chargerDonnees();
            ajouterHistorique(id, "SUPPRESSION", "Agriculteur supprimé: " + nomAg);
            onClearForm();
            QMessageBox::information(this, "Succès", "Agriculteur supprimé avec succès!");
        } else {
            QMessageBox::critical(this, "Erreur SQL", query.lastError().text());
        }
    }
}

void MainWindow::onNouveauAgriculteur()
{
    onClearForm();
    ui->cinEdit->setFocus();
}

// ============================================================
// 🔍 RECHERCHER (Oracle SELECT avec filtre)
// ============================================================
void MainWindow::onRechercher()
{
    QString searchText = ui->searchInput->text().trimmed();

    if (searchText.isEmpty()) {
        afficherTableau();
        return;
    }

    QSqlQuery query(Connection::createInstance().getDB());
    query.prepare(
        "SELECT ID, CIN, NOM, ADRESSE, TELEPHONE, EMAIL, SUPERFICIE, OLIVIERS "
        "FROM AGRICULTEUR "
        "WHERE UPPER(NOM) LIKE UPPER(:s) OR UPPER(CIN) LIKE UPPER(:s2)"
        );
    query.bindValue(":s",  "%" + searchText + "%");
    query.bindValue(":s2", "%" + searchText + "%");

    ui->tableWidget->setRowCount(0);
    int row = 0;
    if (query.exec()) {
        while (query.next()) {
            ui->tableWidget->insertRow(row);
            for (int col = 0; col < 8; col++) {
                ui->tableWidget->setItem(row, col,
                                         new QTableWidgetItem(query.value(col).toString()));
            }
            row++;
        }
    } else {
        QMessageBox::critical(this, "Erreur SQL", query.lastError().text());
    }
}

void MainWindow::onTableSelectionChanged()
{
    if (ui->tableWidget->selectedItems().isEmpty()) {
        selectedIndex = -1;
        return;
    }

    int row = ui->tableWidget->row(ui->tableWidget->selectedItems()[0]);

    // Trouver l'index réel dans la liste via le CIN (unique)
    QString cin = ui->tableWidget->item(row, 1)->text();
    for (int i = 0; i < agriculteurs.size(); ++i) {
        if (agriculteurs[i].getCin() == cin) {
            selectedIndex = i;
            afficherAgriculteurdansForm(agriculteurs[i]);
            actualiserBoutonsFichier();
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
    ui->searchInput->clear();

    selectedIndex = -1;
    ui->tableWidget->clearSelection();
    actualiserBoutonsFichier();
}

// ============================================================
// 📜 HISTORIQUE
// ============================================================
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
    // Tentative d'insertion dans la table HISTORIQUE si elle existe
    QSqlQuery query(Connection::createInstance().getDB());
    query.prepare(
        "INSERT INTO HISTORIQUE (AGRICULTEUR_ID, ACTION, DETAILS, DATE_HEURE, UTILISATEUR) "
        "VALUES (:aid, :action, :details, :dh, :user)"
        );
    query.bindValue(":aid",     agriculteurId);
    query.bindValue(":action",  action);
    query.bindValue(":details", details);
    query.bindValue(":dh",      QDateTime::currentDateTime());
    query.bindValue(":user",    "Admin");

    if (!query.exec()) {
        // Fallback : stockage en mémoire si la table n'existe pas
        qDebug() << "Historique en mémoire uniquement :" << query.lastError().text();
        static int hId = 1;
        Historique h(hId++, agriculteurId, action, details,
                     QDateTime::currentDateTime(), "Admin");
        historique.append(h);
    }
}

// ============================================================
// 📈 STATISTIQUES
// ============================================================
void MainWindow::onAfficherStatsOliviers()
{
    if (agriculteurs.isEmpty()) {
        QMessageBox::information(this, "Statistiques", "Aucun agriculteur enregistré.");
        return;
    }
    StatsDialog dlg(agriculteurs, StatsDialog::OliviersStats, this);
    dlg.exec();
}

void MainWindow::onAfficherStatsSuperficie()
{
    if (agriculteurs.isEmpty()) {
        QMessageBox::information(this, "Statistiques", "Aucun agriculteur enregistré.");
        return;
    }
    StatsDialog dlg(agriculteurs, StatsDialog::SuperficieStats, this);
    dlg.exec();
}

// ============================================================
// 📄 EXPORT PDF
// ============================================================
void MainWindow::onExportPDF()
{
    QString fileName = QFileDialog::getSaveFileName(this, "Exporter en PDF",
                                                    "", "PDF Files (*.pdf)");

    if (fileName.isEmpty()) return;

    if (!fileName.endsWith(".pdf", Qt::CaseInsensitive)) {
        fileName += ".pdf";
    }

    QPdfWriter writer(fileName);
    writer.setPageSize(QPageSize(QPageSize::A4));
    writer.setResolution(96);

    QPainter painter(&writer);
    painter.setFont(QFont("Arial", 14, QFont::Bold));
    painter.drawText(100, 100, "Liste des Agriculteurs - Smart Oil Press");

    painter.setFont(QFont("Arial", 9));
    int y = 180;
    painter.drawText(100, y, QString("Généré le : %1")
                                 .arg(QDateTime::currentDateTime().toString("dd/MM/yyyy hh:mm")));

    y += 40;
    painter.setFont(QFont("Arial", 10, QFont::Bold));
    painter.drawText(100, y, "ID   CIN            Nom                    Téléphone       Superficie   Oliviers");
    y += 20;
    painter.drawLine(100, y, 1100, y);
    y += 20;

    painter.setFont(QFont("Arial", 9));
    for (const Agriculteur &ag : agriculteurs) {
        QString line = QString("%1   %2   %3   %4   %5 ha   %6")
        .arg(ag.getId(), 4)
            .arg(ag.getCin(), -12)
            .arg(ag.getNom().leftJustified(20, ' ', true))
            .arg(ag.getTelephone().leftJustified(12, ' ', true))
            .arg(ag.getSuperficie(), -8)
            .arg(ag.getOliviers());
        painter.drawText(100, y, line);
        y += 20;
        if (y > 1500) {
            writer.newPage();
            y = 100;
        }
    }

    painter.end();
    QMessageBox::information(this, "Export", "PDF exporté: " + fileName);
}

void MainWindow::exporterEnPDF()
{
    // Conservé pour compatibilité - logique déplacée dans onExportPDF
}

// ============================================================
// 📊 EXPORT EXCEL / CSV
// ============================================================
void MainWindow::onExportExcel()
{
    QString fileName = QFileDialog::getSaveFileName(this, "Exporter en Excel",
                                                    "", "Excel Files (*.csv)");

    if (fileName.isEmpty()) return;

    if (!fileName.endsWith(".csv", Qt::CaseInsensitive)) {
        fileName += ".csv";
    }

    QFile file(fileName);
    if (file.open(QIODevice::WriteOnly | QIODevice::Text)) {
        QTextStream out(&file);
        out << "ID;CIN;Nom;Adresse;Telephone;Email;Superficie;Oliviers\n";
        for (const Agriculteur &ag : agriculteurs) {
            out << ag.getId() << ";"
                << ag.getCin() << ";"
                << ag.getNom() << ";"
                << ag.getAdresse() << ";"
                << ag.getTelephone() << ";"
                << ag.getEmail() << ";"
                << ag.getSuperficie() << ";"
                << ag.getOliviers() << "\n";
        }
        file.close();
        QMessageBox::information(this, "Export", "CSV exporté: " + fileName);
    } else {
        QMessageBox::critical(this, "Erreur", "Impossible de créer le fichier CSV");
    }
}

void MainWindow::exporterEnExcel()
{
    // Conservé pour compatibilité - logique déplacée dans onExportExcel
}

// ============================================================
// 📎 PIÈCES JOINTES
// ============================================================

// Returns the stored file path for a given agriculteur ID (empty if none)
QString MainWindow::cheminFichierJoint(int id) const
{
    QSettings s("SmartOilPress", "Attachments");
    return s.value(QString("ag_%1").arg(id)).toString();
}

// Enables/disables the "Ouvrir" button and updates the join-button label
void MainWindow::actualiserBoutonsFichier()
{
    bool hasFile = false;
    if (selectedIndex >= 0) {
        QString path = cheminFichierJoint(agriculteurs[selectedIndex].getId());
        hasFile = !path.isEmpty() && QFile::exists(path);
    }

    ui->ouvrirFichierBtn->setEnabled(hasFile);

    if (hasFile) {
        QString path = cheminFichierJoint(agriculteurs[selectedIndex].getId());
        QString name = QFileInfo(path).fileName();
        if (name.length() > 16) name = name.left(13) + "...";
        ui->pieceJointeBtn->setText("\U0001f4ce " + name);
    } else {
        ui->pieceJointeBtn->setText("\U0001f4ce Joindre fichier");
    }
}

// Opens a file dialog, copies the selected file to ./attachments/, saves path in QSettings
void MainWindow::onJoindreFichier()
{
    if (selectedIndex < 0) {
        QMessageBox::warning(this, "S\u00e9lection requise",
                             "Veuillez s\u00e9lectionner un agriculteur dans la liste.");
        return;
    }

    QString src = QFileDialog::getOpenFileName(this,
        "S\u00e9lectionner un fichier \u00e0 joindre",
        QDir::homePath(),
        "Fichiers support\u00e9s (*.pdf *.png *.jpg *.jpeg *.bmp *.gif *.tif *.tiff);;"
        "PDF (*.pdf);;Images (*.png *.jpg *.jpeg *.bmp *.gif *.tif *.tiff)");

    if (src.isEmpty()) return;

    // Ensure attachments directory exists
    QString attachDir = QCoreApplication::applicationDirPath() + "/attachments";
    QDir().mkpath(attachDir);

    int     id      = agriculteurs[selectedIndex].getId();
    QString ext     = QFileInfo(src).suffix();
    QString base    = QFileInfo(src).completeBaseName();
    QString dstName = QString("ag_%1_%2.%3").arg(id).arg(base).arg(ext);
    QString dst     = attachDir + "/" + dstName;

    // Remove any previously stored file for this agriculteur
    QSettings s("SmartOilPress", "Attachments");
    QString oldPath = s.value(QString("ag_%1").arg(id)).toString();
    if (!oldPath.isEmpty() && oldPath != dst && QFile::exists(oldPath))
        QFile::remove(oldPath);

    if (QFile::exists(dst)) QFile::remove(dst);

    if (!QFile::copy(src, dst)) {
        QMessageBox::critical(this, "Erreur",
                              "Impossible de copier le fichier.\n" + dst);
        return;
    }

    s.setValue(QString("ag_%1").arg(id), dst);
    actualiserBoutonsFichier();

    QMessageBox::information(this, "Fichier joint",
        QString("Fichier joint \u00e0 <b>%1</b> avec succ\u00e8s.<br><br>"
                "<span style='color:#555;font-size:11px;'>%2</span>")
            .arg(agriculteurs[selectedIndex].getNom(), QFileInfo(dst).fileName()));
}

// Opens the attached file with the system default application
void MainWindow::onOuvrirFichier()
{
    if (selectedIndex < 0) return;

    QString path = cheminFichierJoint(agriculteurs[selectedIndex].getId());
    if (path.isEmpty() || !QFile::exists(path)) {
        QMessageBox::warning(this, "Fichier introuvable",
                             "Le fichier joint est introuvable ou a \u00e9t\u00e9 supprim\u00e9.");
        actualiserBoutonsFichier();
        return;
    }
    QDesktopServices::openUrl(QUrl::fromLocalFile(path));
}
