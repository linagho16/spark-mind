#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QMainWindow>
#include <QDate>
#include <QPushButton>
#include <QVector>

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

private:
    struct StockItem {
        QString id;
        QString typeProduit;
        int quantite = 0;
        QString unite;
        QDate dateEntree;
        QString emplacement;
        int seuilAlerte = 0;
    };

    Ui::MainWindow *ui;
    QVector<StockItem> m_items;
    QVector<QPushButton *> m_navButtons;

    void setupTable();
    void setupSidebarNav();
    void refreshTable(const QString &filterText);
    int selectedItemIndex() const;
    bool openStockDialog(StockItem &item, bool isEdit);
    void setActiveSidebarButton(QPushButton *activeButton);
    void updateAlertsForIndex(int index);
    void showAlertsDialog();
    void showTopUpDialog();
    void exportCsv();
    void showLocationsDialog();

private slots:
    void onAddClicked();
    void onEditClicked();
    void onDeleteClicked();
    void onSearchChanged(const QString &text);
    void onTableDoubleClicked();
    void onSelectionChanged();
    void onAlertsClicked();
    void onTopUpClicked();
    void onExportClicked();
    void onLocationsClicked();
};
#endif // MAINWINDOW_H
