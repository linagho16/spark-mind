/********************************************************************************
** Form generated from reading UI file 'mainwindow.ui'
**
** Created by: Qt User Interface Compiler version 6.7.3
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_MAINWINDOW_H
#define UI_MAINWINDOW_H

#include <QtCore/QVariant>
#include <QtWidgets/QApplication>
#include <QtWidgets/QComboBox>
#include <QtWidgets/QFrame>
#include <QtWidgets/QGridLayout>
#include <QtWidgets/QGroupBox>
#include <QtWidgets/QHBoxLayout>
#include <QtWidgets/QHeaderView>
#include <QtWidgets/QLabel>
#include <QtWidgets/QLineEdit>
#include <QtWidgets/QMainWindow>
#include <QtWidgets/QMenuBar>
#include <QtWidgets/QPushButton>
#include <QtWidgets/QSpacerItem>
#include <QtWidgets/QStatusBar>
#include <QtWidgets/QTableWidget>
#include <QtWidgets/QVBoxLayout>
#include <QtWidgets/QWidget>

QT_BEGIN_NAMESPACE

class Ui_MainWindow
{
public:
    QWidget *centralwidget;
    QHBoxLayout *mainLayout;
    QFrame *sidebar;
    QVBoxLayout *sidebarLayout;
    QLabel *sidebarTitle;
    QPushButton *navActive;
    QPushButton *navButton_2;
    QPushButton *navButton_3;
    QPushButton *navButton_4;
    QPushButton *navAccent;
    QSpacerItem *sidebarSpacer;
    QVBoxLayout *contentLayout;
    QFrame *topBar;
    QHBoxLayout *topBarLayout;
    QVBoxLayout *titleLayout;
    QLabel *titleLabel;
    QLabel *subTitleLabel;
    QSpacerItem *topSpacer;
    QPushButton *addQuickButton;
    QFrame *filterBar;
    QHBoxLayout *filterLayout;
    QLineEdit *searchLineEdit;
    QComboBox *filterStatusCombo;
    QPushButton *filterButton;
    QPushButton *resetFilterButton;
    QGroupBox *detailsGroup;
    QGridLayout *detailsLayout;
    QLabel *idLabel;
    QLineEdit *idLineEdit;
    QLabel *farmerLabel;
    QLineEdit *farmerLineEdit;
    QLabel *dateLabel;
    QLineEdit *dateLineEdit;
    QLabel *qtyLabel;
    QLineEdit *qtyLineEdit;
    QLabel *priceLabel;
    QLineEdit *priceLineEdit;
    QLabel *totalLabel;
    QLineEdit *totalLineEdit;
    QLabel *statusLabel;
    QComboBox *statusCombo;
    QLabel *livreurLabel;
    QLineEdit *livreurLineEdit;
    QLabel *emailLabel;
    QLineEdit *emailLineEdit;
    QHBoxLayout *crudButtonsLayout;
    QPushButton *addButton;
    QPushButton *updateButton;
    QPushButton *deleteButton;
    QPushButton *clearButton;
    QFrame *tableActionBar;
    QHBoxLayout *tableActionLayout;
    QComboBox *sortColumnCombo;
    QComboBox *sortOrderCombo;
    QPushButton *sortButton;
    QSpacerItem *tableActionSpacer;
    QPushButton *exportButton;
    QTableWidget *tableWidget;
    QMenuBar *menubar;
    QStatusBar *statusbar;

    void setupUi(QMainWindow *MainWindow)
    {
        if (MainWindow->objectName().isEmpty())
            MainWindow->setObjectName("MainWindow");
        MainWindow->resize(1100, 720);
        MainWindow->setStyleSheet(QString::fromUtf8("QMainWindow, QWidget {\n"
"  font-family: \"Segoe UI\";\n"
"  font-size: 14px;\n"
"  background: #F5F5F5;\n"
"  color: #1F2937;\n"
"}\n"
"#topBar {\n"
"  background: #1F4788;\n"
"  border-radius: 10px;\n"
"}\n"
"QFrame#sidebar {\n"
"  background: qlineargradient(x1:0, y1:0, x2:0, y2:1,\n"
"    stop:0 #5E8C2A, stop:1 #4C7A22);\n"
"  border-right: 1px solid #3E5F1A;\n"
"}\n"
"QLabel#sidebarTitle {\n"
"  color: #F5F5F5;\n"
"  font-weight: 700;\n"
"  font-size: 13px;\n"
"  margin-bottom: 10px;\n"
"}\n"
"QPushButton#navActive,\n"
"QPushButton#navButton_2,\n"
"QPushButton#navButton_3,\n"
"QPushButton#navButton_4,\n"
"QPushButton#navAccent {\n"
"  text-align: left;\n"
"  padding: 12px 16px;\n"
"  border-radius: 10px;\n"
"  font-weight: 600;\n"
"}\n"
"QPushButton[navState=\"normal\"] {\n"
"  background-color: #3E6E1F;\n"
"  color: #FFFFFF;\n"
"  border: 2px solid #2F5E19;\n"
"}\n"
"QPushButton[navState=\"active\"] {\n"
"  background-color: #F2B705;\n"
"  color: #FFFFFF;\n"
"  border: 2px solid #C99305;\n"
"  font-weig"
                        "ht: 700;\n"
"}\n"
"#titleLabel {\n"
"  color: #FBC02D;\n"
"  font-size: 22px;\n"
"  font-weight: 700;\n"
"}\n"
"#subTitleLabel {\n"
"  color: #E8ECF4;\n"
"  font-size: 12px;\n"
"}\n"
"#filterBar, #tableActionBar {\n"
"  background: #FFFFFF;\n"
"  border-radius: 10px;\n"
"  border: 1px solid #E0E0E0;\n"
"}\n"
"#detailsGroup {\n"
"  background: #FFFFFF;\n"
"  border-radius: 10px;\n"
"  border: 1px solid #E0E0E0;\n"
"  margin-top: 6px;\n"
"}\n"
"QLineEdit, QComboBox {\n"
"  background: #FFFFFF;\n"
"  border: 1px solid #D0D0D0;\n"
"  border-radius: 6px;\n"
"  padding: 6px 10px;\n"
"}\n"
"QLineEdit:focus, QComboBox:focus {\n"
"  border: 1px solid #2E7D32;\n"
"}\n"
"QPushButton {\n"
"  border: none;\n"
"  border-radius: 6px;\n"
"  padding: 8px 14px;\n"
"  font-weight: 600;\n"
"  background: #2E7D32;\n"
"  color: white;\n"
"}\n"
"QPushButton#filterButton, QPushButton#exportButton {\n"
"  background: #FBC02D;\n"
"  color: #2B2B2B;\n"
"}\n"
"QPushButton#resetFilterButton, QPushButton#clearButton {\n"
"  background: #9E"
                        "9E9E;\n"
"  color: white;\n"
"}\n"
"QPushButton#deleteButton {\n"
"  background: #C62828;\n"
"  color: white;\n"
"}\n"
"QTableWidget {\n"
"  background: #FFFFFF;\n"
"  border: 1px solid #E0E0E0;\n"
"  border-radius: 10px;\n"
"  gridline-color: #EAEAEA;\n"
"  selection-background-color: #E8F5E9;\n"
"  selection-color: #1F2937;\n"
"}\n"
"QHeaderView::section {\n"
"  background: #1F4788;\n"
"  color: white;\n"
"  padding: 8px;\n"
"  border: none;\n"
"  font-weight: 600;\n"
"}"));
        centralwidget = new QWidget(MainWindow);
        centralwidget->setObjectName("centralwidget");
        mainLayout = new QHBoxLayout(centralwidget);
        mainLayout->setSpacing(14);
        mainLayout->setObjectName("mainLayout");
        mainLayout->setContentsMargins(20, 20, 20, 20);
        sidebar = new QFrame(centralwidget);
        sidebar->setObjectName("sidebar");
        sidebar->setMinimumSize(QSize(210, 0));
        sidebarLayout = new QVBoxLayout(sidebar);
        sidebarLayout->setObjectName("sidebarLayout");
        sidebarTitle = new QLabel(sidebar);
        sidebarTitle->setObjectName("sidebarTitle");

        sidebarLayout->addWidget(sidebarTitle);

        navActive = new QPushButton(sidebar);
        navActive->setObjectName("navActive");

        sidebarLayout->addWidget(navActive);

        navButton_2 = new QPushButton(sidebar);
        navButton_2->setObjectName("navButton_2");

        sidebarLayout->addWidget(navButton_2);

        navButton_3 = new QPushButton(sidebar);
        navButton_3->setObjectName("navButton_3");

        sidebarLayout->addWidget(navButton_3);

        navButton_4 = new QPushButton(sidebar);
        navButton_4->setObjectName("navButton_4");

        sidebarLayout->addWidget(navButton_4);

        navAccent = new QPushButton(sidebar);
        navAccent->setObjectName("navAccent");

        sidebarLayout->addWidget(navAccent);

        sidebarSpacer = new QSpacerItem(20, 200, QSizePolicy::Policy::Minimum, QSizePolicy::Policy::Expanding);

        sidebarLayout->addItem(sidebarSpacer);


        mainLayout->addWidget(sidebar);

        contentLayout = new QVBoxLayout();
        contentLayout->setSpacing(14);
        contentLayout->setObjectName("contentLayout");
        topBar = new QFrame(centralwidget);
        topBar->setObjectName("topBar");
        topBar->setFrameShape(QFrame::Shape::StyledPanel);
        topBarLayout = new QHBoxLayout(topBar);
        topBarLayout->setObjectName("topBarLayout");
        topBarLayout->setContentsMargins(16, 12, 16, 12);
        titleLayout = new QVBoxLayout();
        titleLayout->setSpacing(2);
        titleLayout->setObjectName("titleLayout");
        titleLabel = new QLabel(topBar);
        titleLabel->setObjectName("titleLabel");

        titleLayout->addWidget(titleLabel);

        subTitleLabel = new QLabel(topBar);
        subTitleLabel->setObjectName("subTitleLabel");

        titleLayout->addWidget(subTitleLabel);


        topBarLayout->addLayout(titleLayout);

        topSpacer = new QSpacerItem(40, 20, QSizePolicy::Policy::Expanding, QSizePolicy::Policy::Minimum);

        topBarLayout->addItem(topSpacer);

        addQuickButton = new QPushButton(topBar);
        addQuickButton->setObjectName("addQuickButton");

        topBarLayout->addWidget(addQuickButton);


        contentLayout->addWidget(topBar);

        filterBar = new QFrame(centralwidget);
        filterBar->setObjectName("filterBar");
        filterBar->setFrameShape(QFrame::Shape::StyledPanel);
        filterLayout = new QHBoxLayout(filterBar);
        filterLayout->setSpacing(12);
        filterLayout->setObjectName("filterLayout");
        filterLayout->setContentsMargins(16, 10, 16, 10);
        searchLineEdit = new QLineEdit(filterBar);
        searchLineEdit->setObjectName("searchLineEdit");

        filterLayout->addWidget(searchLineEdit);

        filterStatusCombo = new QComboBox(filterBar);
        filterStatusCombo->addItem(QString());
        filterStatusCombo->addItem(QString());
        filterStatusCombo->addItem(QString());
        filterStatusCombo->addItem(QString());
        filterStatusCombo->setObjectName("filterStatusCombo");

        filterLayout->addWidget(filterStatusCombo);

        filterButton = new QPushButton(filterBar);
        filterButton->setObjectName("filterButton");

        filterLayout->addWidget(filterButton);

        resetFilterButton = new QPushButton(filterBar);
        resetFilterButton->setObjectName("resetFilterButton");

        filterLayout->addWidget(resetFilterButton);


        contentLayout->addWidget(filterBar);

        detailsGroup = new QGroupBox(centralwidget);
        detailsGroup->setObjectName("detailsGroup");
        detailsLayout = new QGridLayout(detailsGroup);
        detailsLayout->setObjectName("detailsLayout");
        detailsLayout->setHorizontalSpacing(12);
        detailsLayout->setVerticalSpacing(10);
        detailsLayout->setContentsMargins(16, 12, 16, 12);
        idLabel = new QLabel(detailsGroup);
        idLabel->setObjectName("idLabel");

        detailsLayout->addWidget(idLabel, 0, 0, 1, 1);

        idLineEdit = new QLineEdit(detailsGroup);
        idLineEdit->setObjectName("idLineEdit");

        detailsLayout->addWidget(idLineEdit, 0, 1, 1, 1);

        farmerLabel = new QLabel(detailsGroup);
        farmerLabel->setObjectName("farmerLabel");

        detailsLayout->addWidget(farmerLabel, 0, 2, 1, 1);

        farmerLineEdit = new QLineEdit(detailsGroup);
        farmerLineEdit->setObjectName("farmerLineEdit");

        detailsLayout->addWidget(farmerLineEdit, 0, 3, 1, 1);

        dateLabel = new QLabel(detailsGroup);
        dateLabel->setObjectName("dateLabel");

        detailsLayout->addWidget(dateLabel, 1, 0, 1, 1);

        dateLineEdit = new QLineEdit(detailsGroup);
        dateLineEdit->setObjectName("dateLineEdit");

        detailsLayout->addWidget(dateLineEdit, 1, 1, 1, 1);

        qtyLabel = new QLabel(detailsGroup);
        qtyLabel->setObjectName("qtyLabel");

        detailsLayout->addWidget(qtyLabel, 1, 2, 1, 1);

        qtyLineEdit = new QLineEdit(detailsGroup);
        qtyLineEdit->setObjectName("qtyLineEdit");

        detailsLayout->addWidget(qtyLineEdit, 1, 3, 1, 1);

        priceLabel = new QLabel(detailsGroup);
        priceLabel->setObjectName("priceLabel");

        detailsLayout->addWidget(priceLabel, 2, 0, 1, 1);

        priceLineEdit = new QLineEdit(detailsGroup);
        priceLineEdit->setObjectName("priceLineEdit");

        detailsLayout->addWidget(priceLineEdit, 2, 1, 1, 1);

        totalLabel = new QLabel(detailsGroup);
        totalLabel->setObjectName("totalLabel");

        detailsLayout->addWidget(totalLabel, 2, 2, 1, 1);

        totalLineEdit = new QLineEdit(detailsGroup);
        totalLineEdit->setObjectName("totalLineEdit");

        detailsLayout->addWidget(totalLineEdit, 2, 3, 1, 1);

        statusLabel = new QLabel(detailsGroup);
        statusLabel->setObjectName("statusLabel");

        detailsLayout->addWidget(statusLabel, 3, 0, 1, 1);

        statusCombo = new QComboBox(detailsGroup);
        statusCombo->addItem(QString());
        statusCombo->addItem(QString());
        statusCombo->addItem(QString());
        statusCombo->setObjectName("statusCombo");

        detailsLayout->addWidget(statusCombo, 3, 1, 1, 1);

        livreurLabel = new QLabel(detailsGroup);
        livreurLabel->setObjectName("livreurLabel");

        detailsLayout->addWidget(livreurLabel, 3, 2, 1, 1);

        livreurLineEdit = new QLineEdit(detailsGroup);
        livreurLineEdit->setObjectName("livreurLineEdit");

        detailsLayout->addWidget(livreurLineEdit, 3, 3, 1, 1);

        emailLabel = new QLabel(detailsGroup);
        emailLabel->setObjectName("emailLabel");

        detailsLayout->addWidget(emailLabel, 4, 0, 1, 1);

        emailLineEdit = new QLineEdit(detailsGroup);
        emailLineEdit->setObjectName("emailLineEdit");

        detailsLayout->addWidget(emailLineEdit, 4, 1, 1, 1);

        crudButtonsLayout = new QHBoxLayout();
        crudButtonsLayout->setSpacing(10);
        crudButtonsLayout->setObjectName("crudButtonsLayout");
        addButton = new QPushButton(detailsGroup);
        addButton->setObjectName("addButton");

        crudButtonsLayout->addWidget(addButton);

        updateButton = new QPushButton(detailsGroup);
        updateButton->setObjectName("updateButton");

        crudButtonsLayout->addWidget(updateButton);

        deleteButton = new QPushButton(detailsGroup);
        deleteButton->setObjectName("deleteButton");

        crudButtonsLayout->addWidget(deleteButton);

        clearButton = new QPushButton(detailsGroup);
        clearButton->setObjectName("clearButton");

        crudButtonsLayout->addWidget(clearButton);


        detailsLayout->addLayout(crudButtonsLayout, 4, 2, 1, 2);


        contentLayout->addWidget(detailsGroup);

        tableActionBar = new QFrame(centralwidget);
        tableActionBar->setObjectName("tableActionBar");
        tableActionBar->setFrameShape(QFrame::Shape::StyledPanel);
        tableActionLayout = new QHBoxLayout(tableActionBar);
        tableActionLayout->setSpacing(12);
        tableActionLayout->setObjectName("tableActionLayout");
        tableActionLayout->setContentsMargins(16, 8, 16, 8);
        sortColumnCombo = new QComboBox(tableActionBar);
        sortColumnCombo->addItem(QString());
        sortColumnCombo->addItem(QString());
        sortColumnCombo->addItem(QString());
        sortColumnCombo->addItem(QString());
        sortColumnCombo->addItem(QString());
        sortColumnCombo->addItem(QString());
        sortColumnCombo->addItem(QString());
        sortColumnCombo->setObjectName("sortColumnCombo");

        tableActionLayout->addWidget(sortColumnCombo);

        sortOrderCombo = new QComboBox(tableActionBar);
        sortOrderCombo->addItem(QString());
        sortOrderCombo->addItem(QString());
        sortOrderCombo->setObjectName("sortOrderCombo");

        tableActionLayout->addWidget(sortOrderCombo);

        sortButton = new QPushButton(tableActionBar);
        sortButton->setObjectName("sortButton");

        tableActionLayout->addWidget(sortButton);

        tableActionSpacer = new QSpacerItem(40, 20, QSizePolicy::Policy::Expanding, QSizePolicy::Policy::Minimum);

        tableActionLayout->addItem(tableActionSpacer);

        exportButton = new QPushButton(tableActionBar);
        exportButton->setObjectName("exportButton");

        tableActionLayout->addWidget(exportButton);


        contentLayout->addWidget(tableActionBar);

        tableWidget = new QTableWidget(centralwidget);
        if (tableWidget->columnCount() < 7)
            tableWidget->setColumnCount(7);
        QTableWidgetItem *__qtablewidgetitem = new QTableWidgetItem();
        tableWidget->setHorizontalHeaderItem(0, __qtablewidgetitem);
        QTableWidgetItem *__qtablewidgetitem1 = new QTableWidgetItem();
        tableWidget->setHorizontalHeaderItem(1, __qtablewidgetitem1);
        QTableWidgetItem *__qtablewidgetitem2 = new QTableWidgetItem();
        tableWidget->setHorizontalHeaderItem(2, __qtablewidgetitem2);
        QTableWidgetItem *__qtablewidgetitem3 = new QTableWidgetItem();
        tableWidget->setHorizontalHeaderItem(3, __qtablewidgetitem3);
        QTableWidgetItem *__qtablewidgetitem4 = new QTableWidgetItem();
        tableWidget->setHorizontalHeaderItem(4, __qtablewidgetitem4);
        QTableWidgetItem *__qtablewidgetitem5 = new QTableWidgetItem();
        tableWidget->setHorizontalHeaderItem(5, __qtablewidgetitem5);
        QTableWidgetItem *__qtablewidgetitem6 = new QTableWidgetItem();
        tableWidget->setHorizontalHeaderItem(6, __qtablewidgetitem6);
        tableWidget->setObjectName("tableWidget");
        tableWidget->setRowCount(0);
        tableWidget->setColumnCount(7);

        contentLayout->addWidget(tableWidget);


        mainLayout->addLayout(contentLayout);

        MainWindow->setCentralWidget(centralwidget);
        menubar = new QMenuBar(MainWindow);
        menubar->setObjectName("menubar");
        menubar->setGeometry(QRect(0, 0, 1100, 25));
        MainWindow->setMenuBar(menubar);
        statusbar = new QStatusBar(MainWindow);
        statusbar->setObjectName("statusbar");
        MainWindow->setStatusBar(statusbar);

        retranslateUi(MainWindow);

        QMetaObject::connectSlotsByName(MainWindow);
    } // setupUi

    void retranslateUi(QMainWindow *MainWindow)
    {
        MainWindow->setWindowTitle(QCoreApplication::translate("MainWindow", "Smart Oil Press - Gestion des Commandes", nullptr));
        sidebarTitle->setText(QCoreApplication::translate("MainWindow", "Smart Oil Press", nullptr));
        navActive->setText(QCoreApplication::translate("MainWindow", "Employ\303\251", nullptr));
        navButton_2->setText(QCoreApplication::translate("MainWindow", "Agriculteur", nullptr));
        navButton_3->setText(QCoreApplication::translate("MainWindow", "Stock", nullptr));
        navButton_4->setText(QCoreApplication::translate("MainWindow", "Extraction", nullptr));
        navAccent->setText(QCoreApplication::translate("MainWindow", "Commande", nullptr));
        titleLabel->setText(QCoreApplication::translate("MainWindow", "Smart Oil Press", nullptr));
        subTitleLabel->setText(QCoreApplication::translate("MainWindow", "Gestion des commandes d'huile", nullptr));
        addQuickButton->setText(QCoreApplication::translate("MainWindow", "Nouvelle commande", nullptr));
        searchLineEdit->setPlaceholderText(QCoreApplication::translate("MainWindow", "Recherche (ID, agriculteur, date...)", nullptr));
        filterStatusCombo->setItemText(0, QCoreApplication::translate("MainWindow", "Tous statuts", nullptr));
        filterStatusCombo->setItemText(1, QCoreApplication::translate("MainWindow", "en attente", nullptr));
        filterStatusCombo->setItemText(2, QCoreApplication::translate("MainWindow", "validee", nullptr));
        filterStatusCombo->setItemText(3, QCoreApplication::translate("MainWindow", "livree", nullptr));

        filterButton->setText(QCoreApplication::translate("MainWindow", "Filtrer", nullptr));
        resetFilterButton->setText(QCoreApplication::translate("MainWindow", "Reinitialiser", nullptr));
        detailsGroup->setTitle(QCoreApplication::translate("MainWindow", "Details commande", nullptr));
        idLabel->setText(QCoreApplication::translate("MainWindow", "ID Commande", nullptr));
        farmerLabel->setText(QCoreApplication::translate("MainWindow", "ID Agriculteur", nullptr));
        dateLabel->setText(QCoreApplication::translate("MainWindow", "Date de commande", nullptr));
        dateLineEdit->setPlaceholderText(QCoreApplication::translate("MainWindow", "YYYY-MM-DD", nullptr));
        qtyLabel->setText(QCoreApplication::translate("MainWindow", "Quantite commandee", nullptr));
        priceLabel->setText(QCoreApplication::translate("MainWindow", "Prix unitaire", nullptr));
        totalLabel->setText(QCoreApplication::translate("MainWindow", "Montant total", nullptr));
        totalLineEdit->setPlaceholderText(QCoreApplication::translate("MainWindow", "Auto", nullptr));
        statusLabel->setText(QCoreApplication::translate("MainWindow", "Statut", nullptr));
        statusCombo->setItemText(0, QCoreApplication::translate("MainWindow", "en attente", nullptr));
        statusCombo->setItemText(1, QCoreApplication::translate("MainWindow", "validee", nullptr));
        statusCombo->setItemText(2, QCoreApplication::translate("MainWindow", "livree", nullptr));

        livreurLabel->setText(QCoreApplication::translate("MainWindow", "ID Livreur", nullptr));
        emailLabel->setText(QCoreApplication::translate("MainWindow", "Email", nullptr));
        emailLineEdit->setPlaceholderText(QCoreApplication::translate("MainWindow", "ex: client@email.com", nullptr));
        addButton->setText(QCoreApplication::translate("MainWindow", "Ajouter", nullptr));
        updateButton->setText(QCoreApplication::translate("MainWindow", "Modifier", nullptr));
        deleteButton->setText(QCoreApplication::translate("MainWindow", "Supprimer", nullptr));
        clearButton->setText(QCoreApplication::translate("MainWindow", "Vider", nullptr));
        sortColumnCombo->setItemText(0, QCoreApplication::translate("MainWindow", "ID Commande", nullptr));
        sortColumnCombo->setItemText(1, QCoreApplication::translate("MainWindow", "ID Agriculteur", nullptr));
        sortColumnCombo->setItemText(2, QCoreApplication::translate("MainWindow", "Date de commande", nullptr));
        sortColumnCombo->setItemText(3, QCoreApplication::translate("MainWindow", "Quantite commandee", nullptr));
        sortColumnCombo->setItemText(4, QCoreApplication::translate("MainWindow", "Prix unitaire", nullptr));
        sortColumnCombo->setItemText(5, QCoreApplication::translate("MainWindow", "Montant total", nullptr));
        sortColumnCombo->setItemText(6, QCoreApplication::translate("MainWindow", "Statut", nullptr));

        sortOrderCombo->setItemText(0, QCoreApplication::translate("MainWindow", "Ascendant", nullptr));
        sortOrderCombo->setItemText(1, QCoreApplication::translate("MainWindow", "Descendant", nullptr));

        sortButton->setText(QCoreApplication::translate("MainWindow", "Trier", nullptr));
        exportButton->setText(QCoreApplication::translate("MainWindow", "Ecrire (CSV)", nullptr));
        QTableWidgetItem *___qtablewidgetitem = tableWidget->horizontalHeaderItem(0);
        ___qtablewidgetitem->setText(QCoreApplication::translate("MainWindow", "ID Commande", nullptr));
        QTableWidgetItem *___qtablewidgetitem1 = tableWidget->horizontalHeaderItem(1);
        ___qtablewidgetitem1->setText(QCoreApplication::translate("MainWindow", "ID Agriculteur", nullptr));
        QTableWidgetItem *___qtablewidgetitem2 = tableWidget->horizontalHeaderItem(2);
        ___qtablewidgetitem2->setText(QCoreApplication::translate("MainWindow", "Date de commande", nullptr));
        QTableWidgetItem *___qtablewidgetitem3 = tableWidget->horizontalHeaderItem(3);
        ___qtablewidgetitem3->setText(QCoreApplication::translate("MainWindow", "Quantite commandee", nullptr));
        QTableWidgetItem *___qtablewidgetitem4 = tableWidget->horizontalHeaderItem(4);
        ___qtablewidgetitem4->setText(QCoreApplication::translate("MainWindow", "Prix unitaire", nullptr));
        QTableWidgetItem *___qtablewidgetitem5 = tableWidget->horizontalHeaderItem(5);
        ___qtablewidgetitem5->setText(QCoreApplication::translate("MainWindow", "Montant total", nullptr));
        QTableWidgetItem *___qtablewidgetitem6 = tableWidget->horizontalHeaderItem(6);
        ___qtablewidgetitem6->setText(QCoreApplication::translate("MainWindow", "Statut", nullptr));
    } // retranslateUi

};

namespace Ui {
    class MainWindow: public Ui_MainWindow {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_MAINWINDOW_H
