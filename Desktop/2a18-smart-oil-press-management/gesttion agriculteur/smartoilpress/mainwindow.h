#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QMainWindow>
#include <QList>
#include <QPalette>
#include <QColor>
#include "agriculteur.h"
#include "culture.h"
#include "historique.h"

QT_BEGIN_NAMESPACE
namespace Ui {
class MainWindow;
}
QT_END_NAMESPACE

class MainWindow : public QMainWindow
{
    Q_OBJECT

public:
    MainWindow(QWidget *parent = nullptr);
    ~MainWindow();

private slots:
    void onAjouter();
    void onModifier();
    void onSupprimer();
    void onRechercher();
    void onTableSelectionChanged();
    void onClearForm();
    void onAfficherCultures();
    void onAfficherHistorique();
    void onExportPDF();
    void onExportExcel();
    void onAfficherStats();

private:
    void setupStyleSheet();
    void setupConnections();
    void chargerDonnees();
    void afficherTableau();
    void afficherAgriculteurdansForm(const Agriculteur &ag);
    void ajouterHistorique(int agriculteurId, const QString &action, const QString &details);
    void afficherStatistiques();
    void exporterEnPDF();
    void exporterEnExcel();
    
    Ui::MainWindow *ui;
    QList<Agriculteur> agriculteurs;
    QList<Culture> cultures;
    QList<Historique> historique;
    int selectedIndex = -1;
};
#endif // MAINWINDOW_H
