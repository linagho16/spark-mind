#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QMainWindow>
#include <QList>
#include "agriculteur.h"
#include "culture.h"
#include "historique.h"

QT_BEGIN_NAMESPACE
namespace Ui {
class MainWindow;
}
QT_END_NAMESPACE

class QComboBox;
class QLineEdit;
class QPushButton;
class QTableWidget;
class QWidget;

class MainWindow : public QMainWindow
{
    Q_OBJECT

public:
    MainWindow(QWidget *parent = nullptr);
    ~MainWindow();

private:
    Ui::MainWindow *ui;

    void setupExtractionUi();
    void setupEmployeeUi();
    void setupStockUi();
    void applyExtractionFilter(const QString &text);
    void addExtractionRow();
    void loadSelectedExtractionRow();
    void modifySelectedExtractionRow();
    void deleteSelectedExtractionRow();
    void exportExtractionPdf();
    void showExtractionStats();
    void setupCommandeUi();
    void showEmployeePage();
    void showExtractionPage();
    void showStockPage();
    void showCommandePage();
    void showAgriculteurPage();
    void setupAgriculteurUi();

    struct CommandeWidgets {
        QWidget *root = nullptr;
        QLineEdit *searchLineEdit = nullptr;
        QComboBox *filterStatusCombo = nullptr;
        QPushButton *filterButton = nullptr;
        QPushButton *resetFilterButton = nullptr;
        QComboBox *sortColumnCombo = nullptr;
        QComboBox *sortOrderCombo = nullptr;
        QPushButton *sortButton = nullptr;
        QPushButton *exportButton = nullptr;
        QTableWidget *tableWidget = nullptr;
        QLineEdit *idLineEdit = nullptr;
        QLineEdit *farmerLineEdit = nullptr;
        QLineEdit *dateLineEdit = nullptr;
        QLineEdit *qtyLineEdit = nullptr;
        QLineEdit *priceLineEdit = nullptr;
        QLineEdit *totalLineEdit = nullptr;
        QLineEdit *livreurLineEdit = nullptr;
        QLineEdit *emailLineEdit = nullptr;
        QLineEdit *emailLivreurLineEdit = nullptr;
        QComboBox *statusCombo = nullptr;
        QPushButton *addButton = nullptr;
        QPushButton *updateButton = nullptr;
        QPushButton *deleteButton = nullptr;
        QPushButton *clearButton = nullptr;
        QPushButton *addQuickButton = nullptr;
    };

    bool resolveCommandeWidgets();
    void setupCommandeConnections();
    void applyCommandeTheme();
    void setupCommandeTable();
    bool commandeReadForm(QString &id, QString &farmerId, QString &date,
                          double &qty, double &price, double &total,
                          QString &livreurId, QString &emailClient,
                          QString &emailLivreur, QString &status);
    void commandeSetRow(int row, const QString &id, const QString &farmerId, const QString &date,
                        double qty, double price, double total,
                        const QString &livreurId, const QString &emailClient,
                        const QString &emailLivreur, const QString &status);
    int commandeSelectedRow() const;
    void commandeFillFormFromRow(int row);
    QString commandeFormatNumber(double value) const;
    QString commandeCsvEscape(const QString &text) const;
    void commandeFilterRows(const QString &query, const QString &status);
    void commandeAdd();
    void commandeUpdate();
    void commandeDelete();
    void commandeClear();
    void commandeFilter();
    void commandeResetFilter();
    void commandeSort();
    void commandeExport();
    void commandeTableSelectionChanged();
    void commandeQuickAdd();

    CommandeWidgets commandeUi_;
    QWidget *commandeRoot = nullptr;
    QWidget *stockRoot = nullptr;
    QWidget *employeeRoot = nullptr;

    struct AgriculteurWidgets {
        QWidget *root = nullptr;
        QLineEdit *idEdit = nullptr;
        QLineEdit *cinEdit = nullptr;
        QLineEdit *nomEdit = nullptr;
        QLineEdit *adresseEdit = nullptr;
        QLineEdit *telephoneEdit = nullptr;
        QLineEdit *emailEdit = nullptr;
        QLineEdit *superficieEdit = nullptr;
        QLineEdit *oliviersEdit = nullptr;
        QLineEdit *searchEdit = nullptr;
        QTableWidget *tableWidget = nullptr;
        QPushButton *ajouterBtn = nullptr;
        QPushButton *modifierBtn = nullptr;
        QPushButton *supprimerBtn = nullptr;
        QPushButton *effacerBtn = nullptr;
        QPushButton *rechercherBtn = nullptr;
        QPushButton *culturesBtn = nullptr;
        QPushButton *historiqueBtn = nullptr;
        QPushButton *statsFormBtn = nullptr;
        QPushButton *exportBtn = nullptr;
    };

    bool resolveAgriculteurWidgets();
    void applyAgriculteurTheme();
    void agriculteurChargerDonnees();
    void agriculteurAfficherTableau();
    void agriculteurAfficherDansForm(const Agriculteur &ag);
    void agriculteurAjouterHistorique(int agriculteurId, const QString &action, const QString &details);
    void agriculteurOnAjouter();
    void agriculteurOnModifier();
    void agriculteurOnSupprimer();
    void agriculteurOnRechercher();
    void agriculteurTableSelectionChanged();
    void agriculteurClearForm();
    void agriculteurOnAfficherCultures();
    void agriculteurOnAfficherHistorique();
    void agriculteurOnAfficherStats();
    void agriculteurOnExportPDF();

    AgriculteurWidgets agriculteurUi_;
    QWidget *agriculteurRoot = nullptr;
    QWidget *agriculteurPage_ = nullptr;
    QList<Agriculteur> agriculteurs_;
    QList<Culture> cultures_;
    QList<Historique> historique_;
    int agriculteurSelectedIndex = -1;
};
#endif // MAINWINDOW_H


