#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QMainWindow>

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
    void onAddClicked();
    void onUpdateClicked();
    void onDeleteClicked();
    void onClearClicked();
    void onFilterClicked();
    void onResetFilterClicked();
    void onSortClicked();
    void onExportClicked();
    void onTableSelectionChanged();
    void onQuickAddClicked();

private:
    void applyStyle();
    void setupTable();
    bool readForm(QString &id, QString &farmerId, QString &date,
                  double &qty, double &price, double &total,
                  QString &livreurId, QString &emailClient,
                  QString &emailLivreur, QString &status);
    void setRow(int row, const QString &id, const QString &farmerId, const QString &date,
                double qty, double price, double total,
                const QString &livreurId, const QString &emailClient,
                const QString &emailLivreur, const QString &status);
    int selectedRow() const;
    void fillFormFromRow(int row);
    QString formatNumber(double value) const;
    QString csvEscape(const QString &text) const;
    void filterRows(const QString &query, const QString &status);

    Ui::MainWindow *ui;
};
#endif // MAINWINDOW_H
