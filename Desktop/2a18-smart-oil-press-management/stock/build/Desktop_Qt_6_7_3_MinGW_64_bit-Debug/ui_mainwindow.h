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
#include <QtWidgets/QFrame>
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
    QLabel *logoLabel;
    QPushButton *navActive;
    QPushButton *navButton_2;
    QPushButton *navButton_3;
    QPushButton *navButton_4;
    QPushButton *navAccent;
    QSpacerItem *sidebarSpacer;
    QVBoxLayout *contentLayout;
    QFrame *topBar;
    QHBoxLayout *topBarLayout;
    QLabel *sectionTitle;
    QSpacerItem *topSpacer2;
    QLabel *userLabel;
    QVBoxLayout *contentBodyLayout;
    QFrame *cardsRow;
    QHBoxLayout *cardsLayout;
    QFrame *cardAlert;
    QVBoxLayout *cardLayout1;
    QLabel *cardTitleAlert;
    QLabel *labelAlertDesc;
    QLabel *alertLow;
    QLabel *alertMid;
    QLabel *alertOk;
    QFrame *cardTopUp;
    QVBoxLayout *cardLayout2;
    QLabel *cardTitleTopUp;
    QLabel *labelTopUpDesc;
    QLabel *labelTopUpPoint1;
    QLabel *labelTopUpPoint2;
    QLabel *labelTopUpPoint3;
    QFrame *cardLocations;
    QVBoxLayout *cardLayout3;
    QLabel *cardTitleLocations;
    QLabel *labelLocDesc;
    QLabel *labelLocPoint1;
    QLabel *labelLocPoint2;
    QFrame *cardInventory;
    QVBoxLayout *tableCardLayout;
    QLabel *cardTitleInventory;
    QHBoxLayout *inventoryToolbarLayout;
    QLineEdit *searchEdit;
    QPushButton *btnAdd;
    QPushButton *btnEdit;
    QPushButton *btnDelete;
    QTableWidget *tableStock;
    QFrame *analyticsRow;
    QHBoxLayout *analyticsLayout;
    QFrame *cardChart;
    QVBoxLayout *chartCardLayout;
    QLabel *cardTitleChart;
    QLabel *labelChartPlaceholder;
    QFrame *cardActions;
    QVBoxLayout *actionsLayout;
    QLabel *cardTitleActions;
    QPushButton *btnAlerts;
    QPushButton *btnTopUp;
    QPushButton *btnExport;
    QPushButton *btnLocations;
    QSpacerItem *contentSpacer;
    QMenuBar *menubar;
    QStatusBar *statusbar;

    void setupUi(QMainWindow *MainWindow)
    {
        if (MainWindow->objectName().isEmpty())
            MainWindow->setObjectName("MainWindow");
        MainWindow->resize(1141, 783);
        centralwidget = new QWidget(MainWindow);
        centralwidget->setObjectName("centralwidget");
        centralwidget->setStyleSheet(QString::fromUtf8("/* Palette */\n"
"QWidget#centralwidget {\n"
"    background-color: #F4EEE4;\n"
"    font-family: \"Segoe UI\";\n"
"    font-size: 12px;\n"
"}\n"
"\n"
"QFrame#topBar {\n"
"    background: qlineargradient(x1:0, y1:0, x2:1, y2:0,\n"
"        stop:0 #2A3B4C, stop:1 #1E2B39);\n"
"    border-radius: 10px;\n"
"}\n"
"\n"
"QLabel#appTitle {\n"
"    color: #F5F5F5;\n"
"    font-size: 17px;\n"
"    font-weight: 700;\n"
"}\n"
"\n"
"QLabel#topIcons {\n"
"    color: #FBC02D;\n"
"    font-size: 14px;\n"
"}\n"
"\n"
"QLabel#userLabel {\n"
"    color: #F5F5F5;\n"
"    font-size: 12px;\n"
"}\n"
"\n"
"QFrame#sidebar {\n"
"    background: qlineargradient(x1:0, y1:0, x2:0, y2:1,\n"
"        stop:0 #2A3B4C, stop:1 #1E2B39);\n"
"    border-right: 1px solid #16202C;\n"
"}\n"
"\n"
"QLabel#sidebarTitle {\n"
"    color: #F5F5F5;\n"
"    font-weight: 700;\n"
"    font-size: 13px;\n"
"}\n"
"\n"
"QPushButton[nav=\"true\"] {\n"
"    background-color: #314A67;\n"
"    border: 1px solid #24364d;\n"
"    color: #FFFFFF;\n"
"    text-align: lef"
                        "t;\n"
"    padding: 12px 16px;\n"
"    border-radius: 8px;\n"
"}\n"
"\n"
"QPushButton[nav=\"true\"]:hover {\n"
"    background-color: #F2B705;\n"
"    border: 1px solid #C99350;\n"
"    color: #1F2937;\n"
"}\n"
"\n"
"QPushButton[nav=\"true\"][active=\"true\"] {\n"
"    background-color: #F2B705;\n"
"    border: 1px solid #C99350;\n"
"    color: #1F2937;\n"
"}\n"
"\n"
"QPushButton[nav=\"true\"][active=\"true\"]:hover {\n"
"    background-color: #F2B705;\n"
"    border: 1px solid #C99350;\n"
"    color: #1F2937;\n"
"}\n"
"\n"
"QLabel#sectionTitle {\n"
"    color: #FFFFFF;\n"
"    font-size: 18px;\n"
"    font-weight: 700;\n"
"}\n"
"\n"
"QPushButton {\n"
"    background-color: #6B8E23;\n"
"    color: #F5F5F5;\n"
"    border: none;\n"
"    padding: 9px 16px;\n"
"    border-radius: 10px;\n"
"}\n"
"\n"
"QPushButton:hover {\n"
"    background-color: #5A7A1E;\n"
"    color: #F5F5F5;\n"
"}\n"
"\n"
"QPushButton#btnSecondary {\n"
"    background-color: #F2B705;\n"
"    color: #2B5978;\n"
"}\n"
"\n"
"QPushButton#btnSecond"
                        "ary:hover {\n"
"    background-color: #E0A800;\n"
"    color: #2B5978;\n"
"}\n"
"\n"
"QPushButton#btnDelete {\n"
"    background-color: #C62828;\n"
"    color: #FFFFFF;\n"
"}\n"
"\n"
"QPushButton#btnDelete:hover {\n"
"    background-color: #B71C1C;\n"
"    color: #FFFFFF;\n"
"}\n"
"\n"
"QLineEdit {\n"
"    background-color: #FFFBF5;\n"
"    border: 1px solid #E3D8C7;\n"
"    border-radius: 10px;\n"
"    padding: 8px 10px;\n"
"    min-height: 30px;\n"
"}\n"
"\n"
"QFrame#cardAlert,\n"
"QFrame#cardTopUp,\n"
"QFrame#cardLocations,\n"
"QFrame#cardInventory,\n"
"QFrame#cardChart,\n"
"QFrame#cardActions {\n"
"    background-color: #FFFFFF;\n"
"    border: 1px solid #E3D8C7;\n"
"    border-radius: 12px;\n"
"}\n"
"\n"
"QLabel#cardTitleAlert,\n"
"QLabel#cardTitleTopUp,\n"
"QLabel#cardTitleLocations,\n"
"QLabel#cardTitleInventory,\n"
"QLabel#cardTitleChart,\n"
"QLabel#cardTitleActions {\n"
"    color: #2B5978;\n"
"    font-weight: 700;\n"
"}\n"
"\n"
"QTableWidget {\n"
"    background-color: #ffffff;\n"
"    gridline-colo"
                        "r: #E3D8C7;\n"
"    selection-background-color: #FFE9B5;\n"
"    selection-color: #2B5978;\n"
"    border: none;\n"
"}\n"
"\n"
"QHeaderView::section {\n"
"    background-color: #2B5978;\n"
"    color: #F5F5F5;\n"
"    padding: 8px 10px;\n"
"    border: none;\n"
"    font-weight: 600;\n"
"}\n"
"\n"
"QTableWidget::item {\n"
"    padding: 6px 8px;\n"
"}\n"
"\n"
"QLabel#alertLow {\n"
"    background-color: #D32F2F;\n"
"    color: #FFFFFF;\n"
"    padding: 4px 10px;\n"
"    border-radius: 8px;\n"
"}\n"
"\n"
"QLabel#alertMid {\n"
"    background-color: #F57C00;\n"
"    color: #FFFFFF;\n"
"    padding: 4px 10px;\n"
"    border-radius: 8px;\n"
"}\n"
"\n"
"QLabel#alertOk {\n"
"    background-color: #2E7D32;\n"
"    color: #FFFFFF;\n"
"    padding: 4px 10px;\n"
"    border-radius: 8px;\n"
"}"));
        mainLayout = new QHBoxLayout(centralwidget);
        mainLayout->setObjectName("mainLayout");
        sidebar = new QFrame(centralwidget);
        sidebar->setObjectName("sidebar");
        sidebar->setMinimumSize(QSize(210, 0));
        sidebarLayout = new QVBoxLayout(sidebar);
        sidebarLayout->setObjectName("sidebarLayout");
        logoLabel = new QLabel(sidebar);
        logoLabel->setObjectName("logoLabel");
        logoLabel->setMinimumSize(QSize(180, 60));
        logoLabel->setMaximumSize(QSize(220, 70));
        logoLabel->setPixmap(QPixmap(QString::fromUtf8(":/assets/resoursers/logo.png")));
        logoLabel->setScaledContents(true);
        logoLabel->setAlignment(Qt::AlignmentFlag::AlignCenter);

        sidebarLayout->addWidget(logoLabel);

        navActive = new QPushButton(sidebar);
        navActive->setObjectName("navActive");
        navActive->setMinimumSize(QSize(160, 64));
        navActive->setMaximumSize(QSize(220, 70));
        navActive->setProperty("nav", QVariant(true));
        navActive->setProperty("active", QVariant(true));

        sidebarLayout->addWidget(navActive);

        navButton_2 = new QPushButton(sidebar);
        navButton_2->setObjectName("navButton_2");
        navButton_2->setMinimumSize(QSize(160, 64));
        navButton_2->setMaximumSize(QSize(220, 70));
        navButton_2->setProperty("nav", QVariant(true));

        sidebarLayout->addWidget(navButton_2);

        navButton_3 = new QPushButton(sidebar);
        navButton_3->setObjectName("navButton_3");
        navButton_3->setMinimumSize(QSize(160, 64));
        navButton_3->setMaximumSize(QSize(220, 70));
        navButton_3->setProperty("nav", QVariant(true));

        sidebarLayout->addWidget(navButton_3);

        navButton_4 = new QPushButton(sidebar);
        navButton_4->setObjectName("navButton_4");
        navButton_4->setMinimumSize(QSize(160, 64));
        navButton_4->setMaximumSize(QSize(220, 70));
        navButton_4->setProperty("nav", QVariant(true));

        sidebarLayout->addWidget(navButton_4);

        navAccent = new QPushButton(sidebar);
        navAccent->setObjectName("navAccent");
        navAccent->setMinimumSize(QSize(160, 64));
        navAccent->setMaximumSize(QSize(220, 70));
        navAccent->setProperty("nav", QVariant(true));

        sidebarLayout->addWidget(navAccent);

        sidebarSpacer = new QSpacerItem(20, 200, QSizePolicy::Policy::Minimum, QSizePolicy::Policy::Expanding);

        sidebarLayout->addItem(sidebarSpacer);


        mainLayout->addWidget(sidebar);

        contentLayout = new QVBoxLayout();
        contentLayout->setObjectName("contentLayout");
        topBar = new QFrame(centralwidget);
        topBar->setObjectName("topBar");
        topBarLayout = new QHBoxLayout(topBar);
        topBarLayout->setObjectName("topBarLayout");
        sectionTitle = new QLabel(topBar);
        sectionTitle->setObjectName("sectionTitle");

        topBarLayout->addWidget(sectionTitle);

        topSpacer2 = new QSpacerItem(40, 20, QSizePolicy::Policy::Expanding, QSizePolicy::Policy::Minimum);

        topBarLayout->addItem(topSpacer2);

        userLabel = new QLabel(topBar);
        userLabel->setObjectName("userLabel");

        topBarLayout->addWidget(userLabel);


        contentLayout->addWidget(topBar);

        contentBodyLayout = new QVBoxLayout();
        contentBodyLayout->setObjectName("contentBodyLayout");
        cardsRow = new QFrame(centralwidget);
        cardsRow->setObjectName("cardsRow");
        cardsLayout = new QHBoxLayout(cardsRow);
        cardsLayout->setObjectName("cardsLayout");
        cardAlert = new QFrame(cardsRow);
        cardAlert->setObjectName("cardAlert");
        cardLayout1 = new QVBoxLayout(cardAlert);
        cardLayout1->setObjectName("cardLayout1");
        cardTitleAlert = new QLabel(cardAlert);
        cardTitleAlert->setObjectName("cardTitleAlert");

        cardLayout1->addWidget(cardTitleAlert);

        labelAlertDesc = new QLabel(cardAlert);
        labelAlertDesc->setObjectName("labelAlertDesc");

        cardLayout1->addWidget(labelAlertDesc);

        alertLow = new QLabel(cardAlert);
        alertLow->setObjectName("alertLow");

        cardLayout1->addWidget(alertLow);

        alertMid = new QLabel(cardAlert);
        alertMid->setObjectName("alertMid");

        cardLayout1->addWidget(alertMid);

        alertOk = new QLabel(cardAlert);
        alertOk->setObjectName("alertOk");

        cardLayout1->addWidget(alertOk);


        cardsLayout->addWidget(cardAlert);

        cardTopUp = new QFrame(cardsRow);
        cardTopUp->setObjectName("cardTopUp");
        cardLayout2 = new QVBoxLayout(cardTopUp);
        cardLayout2->setObjectName("cardLayout2");
        cardTitleTopUp = new QLabel(cardTopUp);
        cardTitleTopUp->setObjectName("cardTitleTopUp");

        cardLayout2->addWidget(cardTitleTopUp);

        labelTopUpDesc = new QLabel(cardTopUp);
        labelTopUpDesc->setObjectName("labelTopUpDesc");

        cardLayout2->addWidget(labelTopUpDesc);

        labelTopUpPoint1 = new QLabel(cardTopUp);
        labelTopUpPoint1->setObjectName("labelTopUpPoint1");

        cardLayout2->addWidget(labelTopUpPoint1);

        labelTopUpPoint2 = new QLabel(cardTopUp);
        labelTopUpPoint2->setObjectName("labelTopUpPoint2");

        cardLayout2->addWidget(labelTopUpPoint2);

        labelTopUpPoint3 = new QLabel(cardTopUp);
        labelTopUpPoint3->setObjectName("labelTopUpPoint3");

        cardLayout2->addWidget(labelTopUpPoint3);


        cardsLayout->addWidget(cardTopUp);

        cardLocations = new QFrame(cardsRow);
        cardLocations->setObjectName("cardLocations");
        cardLayout3 = new QVBoxLayout(cardLocations);
        cardLayout3->setObjectName("cardLayout3");
        cardTitleLocations = new QLabel(cardLocations);
        cardTitleLocations->setObjectName("cardTitleLocations");

        cardLayout3->addWidget(cardTitleLocations);

        labelLocDesc = new QLabel(cardLocations);
        labelLocDesc->setObjectName("labelLocDesc");

        cardLayout3->addWidget(labelLocDesc);

        labelLocPoint1 = new QLabel(cardLocations);
        labelLocPoint1->setObjectName("labelLocPoint1");

        cardLayout3->addWidget(labelLocPoint1);

        labelLocPoint2 = new QLabel(cardLocations);
        labelLocPoint2->setObjectName("labelLocPoint2");

        cardLayout3->addWidget(labelLocPoint2);


        cardsLayout->addWidget(cardLocations);


        contentBodyLayout->addWidget(cardsRow);

        cardInventory = new QFrame(centralwidget);
        cardInventory->setObjectName("cardInventory");
        tableCardLayout = new QVBoxLayout(cardInventory);
        tableCardLayout->setObjectName("tableCardLayout");
        cardTitleInventory = new QLabel(cardInventory);
        cardTitleInventory->setObjectName("cardTitleInventory");

        tableCardLayout->addWidget(cardTitleInventory);

        inventoryToolbarLayout = new QHBoxLayout();
        inventoryToolbarLayout->setObjectName("inventoryToolbarLayout");
        searchEdit = new QLineEdit(cardInventory);
        searchEdit->setObjectName("searchEdit");

        inventoryToolbarLayout->addWidget(searchEdit);

        btnAdd = new QPushButton(cardInventory);
        btnAdd->setObjectName("btnAdd");

        inventoryToolbarLayout->addWidget(btnAdd);

        btnEdit = new QPushButton(cardInventory);
        btnEdit->setObjectName("btnEdit");

        inventoryToolbarLayout->addWidget(btnEdit);

        btnDelete = new QPushButton(cardInventory);
        btnDelete->setObjectName("btnDelete");

        inventoryToolbarLayout->addWidget(btnDelete);


        tableCardLayout->addLayout(inventoryToolbarLayout);

        tableStock = new QTableWidget(cardInventory);
        if (tableStock->columnCount() < 7)
            tableStock->setColumnCount(7);
        QTableWidgetItem *__qtablewidgetitem = new QTableWidgetItem();
        tableStock->setHorizontalHeaderItem(0, __qtablewidgetitem);
        QTableWidgetItem *__qtablewidgetitem1 = new QTableWidgetItem();
        tableStock->setHorizontalHeaderItem(1, __qtablewidgetitem1);
        QTableWidgetItem *__qtablewidgetitem2 = new QTableWidgetItem();
        tableStock->setHorizontalHeaderItem(2, __qtablewidgetitem2);
        QTableWidgetItem *__qtablewidgetitem3 = new QTableWidgetItem();
        tableStock->setHorizontalHeaderItem(3, __qtablewidgetitem3);
        QTableWidgetItem *__qtablewidgetitem4 = new QTableWidgetItem();
        tableStock->setHorizontalHeaderItem(4, __qtablewidgetitem4);
        QTableWidgetItem *__qtablewidgetitem5 = new QTableWidgetItem();
        tableStock->setHorizontalHeaderItem(5, __qtablewidgetitem5);
        QTableWidgetItem *__qtablewidgetitem6 = new QTableWidgetItem();
        tableStock->setHorizontalHeaderItem(6, __qtablewidgetitem6);
        if (tableStock->rowCount() < 6)
            tableStock->setRowCount(6);
        tableStock->setObjectName("tableStock");
        tableStock->setRowCount(6);
        tableStock->setColumnCount(7);

        tableCardLayout->addWidget(tableStock);


        contentBodyLayout->addWidget(cardInventory);

        analyticsRow = new QFrame(centralwidget);
        analyticsRow->setObjectName("analyticsRow");
        analyticsLayout = new QHBoxLayout(analyticsRow);
        analyticsLayout->setObjectName("analyticsLayout");
        cardChart = new QFrame(analyticsRow);
        cardChart->setObjectName("cardChart");
        chartCardLayout = new QVBoxLayout(cardChart);
        chartCardLayout->setObjectName("chartCardLayout");
        cardTitleChart = new QLabel(cardChart);
        cardTitleChart->setObjectName("cardTitleChart");

        chartCardLayout->addWidget(cardTitleChart);

        labelChartPlaceholder = new QLabel(cardChart);
        labelChartPlaceholder->setObjectName("labelChartPlaceholder");
        labelChartPlaceholder->setMinimumSize(QSize(420, 180));
        labelChartPlaceholder->setAlignment(Qt::AlignmentFlag::AlignCenter);

        chartCardLayout->addWidget(labelChartPlaceholder);


        analyticsLayout->addWidget(cardChart);

        cardActions = new QFrame(analyticsRow);
        cardActions->setObjectName("cardActions");
        actionsLayout = new QVBoxLayout(cardActions);
        actionsLayout->setObjectName("actionsLayout");
        cardTitleActions = new QLabel(cardActions);
        cardTitleActions->setObjectName("cardTitleActions");

        actionsLayout->addWidget(cardTitleActions);

        btnAlerts = new QPushButton(cardActions);
        btnAlerts->setObjectName("btnAlerts");

        actionsLayout->addWidget(btnAlerts);

        btnTopUp = new QPushButton(cardActions);
        btnTopUp->setObjectName("btnTopUp");

        actionsLayout->addWidget(btnTopUp);

        btnExport = new QPushButton(cardActions);
        btnExport->setObjectName("btnExport");

        actionsLayout->addWidget(btnExport);

        btnLocations = new QPushButton(cardActions);
        btnLocations->setObjectName("btnLocations");

        actionsLayout->addWidget(btnLocations);


        analyticsLayout->addWidget(cardActions);


        contentBodyLayout->addWidget(analyticsRow);


        contentLayout->addLayout(contentBodyLayout);

        contentSpacer = new QSpacerItem(20, 20, QSizePolicy::Policy::Minimum, QSizePolicy::Policy::Expanding);

        contentLayout->addItem(contentSpacer);


        mainLayout->addLayout(contentLayout);

        MainWindow->setCentralWidget(centralwidget);
        menubar = new QMenuBar(MainWindow);
        menubar->setObjectName("menubar");
        menubar->setGeometry(QRect(0, 0, 1141, 26));
        MainWindow->setMenuBar(menubar);
        statusbar = new QStatusBar(MainWindow);
        statusbar->setObjectName("statusbar");
        MainWindow->setStatusBar(statusbar);

        retranslateUi(MainWindow);

        QMetaObject::connectSlotsByName(MainWindow);
    } // setupUi

    void retranslateUi(QMainWindow *MainWindow)
    {
        MainWindow->setWindowTitle(QCoreApplication::translate("MainWindow", "Smart Oil Press", nullptr));
        navActive->setText(QCoreApplication::translate("MainWindow", "Employ\303\251", nullptr));
        navButton_2->setText(QCoreApplication::translate("MainWindow", "Agriculteur", nullptr));
        navButton_3->setText(QCoreApplication::translate("MainWindow", "Stock", nullptr));
        navButton_4->setText(QCoreApplication::translate("MainWindow", "Extraction", nullptr));
        navAccent->setText(QCoreApplication::translate("MainWindow", "Commande", nullptr));
        sectionTitle->setText(QCoreApplication::translate("MainWindow", "Gestion de Stock", nullptr));
        userLabel->setText(QCoreApplication::translate("MainWindow", "Bienvenue, Amir Mansour", nullptr));
        cardTitleAlert->setText(QCoreApplication::translate("MainWindow", "SmartStock Alert\342\204\242", nullptr));
        labelAlertDesc->setText(QCoreApplication::translate("MainWindow", "Surveillance automatique des seuils critiques", nullptr));
        alertLow->setText(QCoreApplication::translate("MainWindow", "Stock faible", nullptr));
        alertMid->setText(QCoreApplication::translate("MainWindow", "Seuil moyen", nullptr));
        alertOk->setText(QCoreApplication::translate("MainWindow", "Niveau optimal", nullptr));
        cardTitleTopUp->setText(QCoreApplication::translate("MainWindow", "SmartTopUp\342\204\242", nullptr));
        labelTopUpDesc->setText(QCoreApplication::translate("MainWindow", "Recommandations de r\303\251approvisionnement", nullptr));
        labelTopUpPoint1->setText(QCoreApplication::translate("MainWindow", "Suggestion bas\303\251e sur seuil minimum", nullptr));
        labelTopUpPoint2->setText(QCoreApplication::translate("MainWindow", "Quantit\303\251 calcul\303\251e via tendances d\342\200\231usage", nullptr));
        labelTopUpPoint3->setText(QCoreApplication::translate("MainWindow", "Int\303\251gration fournisseurs / achats", nullptr));
        cardTitleLocations->setText(QCoreApplication::translate("MainWindow", "Gestion des emplacements", nullptr));
        labelLocDesc->setText(QCoreApplication::translate("MainWindow", "Suivi par zone, rack et entrep\303\264t", nullptr));
        labelLocPoint1->setText(QCoreApplication::translate("MainWindow", "Capacit\303\251 par emplacement", nullptr));
        labelLocPoint2->setText(QCoreApplication::translate("MainWindow", "Historique des mouvements", nullptr));
        cardTitleInventory->setText(QCoreApplication::translate("MainWindow", "Inventaire", nullptr));
        searchEdit->setPlaceholderText(QCoreApplication::translate("MainWindow", "Rechercher...", nullptr));
        btnAdd->setText(QCoreApplication::translate("MainWindow", "Ajouter", nullptr));
        btnEdit->setText(QCoreApplication::translate("MainWindow", "Modifier", nullptr));
        btnDelete->setText(QCoreApplication::translate("MainWindow", "Supprimer", nullptr));
        QTableWidgetItem *___qtablewidgetitem = tableStock->horizontalHeaderItem(0);
        ___qtablewidgetitem->setText(QCoreApplication::translate("MainWindow", "ID Stock", nullptr));
        QTableWidgetItem *___qtablewidgetitem1 = tableStock->horizontalHeaderItem(1);
        ___qtablewidgetitem1->setText(QCoreApplication::translate("MainWindow", "Type de produit", nullptr));
        QTableWidgetItem *___qtablewidgetitem2 = tableStock->horizontalHeaderItem(2);
        ___qtablewidgetitem2->setText(QCoreApplication::translate("MainWindow", "Quantit\303\251 disponible", nullptr));
        QTableWidgetItem *___qtablewidgetitem3 = tableStock->horizontalHeaderItem(3);
        ___qtablewidgetitem3->setText(QCoreApplication::translate("MainWindow", "Unit\303\251", nullptr));
        QTableWidgetItem *___qtablewidgetitem4 = tableStock->horizontalHeaderItem(4);
        ___qtablewidgetitem4->setText(QCoreApplication::translate("MainWindow", "Date d\342\200\231entr\303\251e", nullptr));
        QTableWidgetItem *___qtablewidgetitem5 = tableStock->horizontalHeaderItem(5);
        ___qtablewidgetitem5->setText(QCoreApplication::translate("MainWindow", "Emplacement", nullptr));
        QTableWidgetItem *___qtablewidgetitem6 = tableStock->horizontalHeaderItem(6);
        ___qtablewidgetitem6->setText(QCoreApplication::translate("MainWindow", "Seuil d\342\200\231alerte", nullptr));
        cardTitleChart->setText(QCoreApplication::translate("MainWindow", "Graphique des tendances de stock", nullptr));
        labelChartPlaceholder->setText(QCoreApplication::translate("MainWindow", "[Zone graphique]", nullptr));
        cardTitleActions->setText(QCoreApplication::translate("MainWindow", "Actions rapides", nullptr));
        btnAlerts->setText(QCoreApplication::translate("MainWindow", "Alertes stock", nullptr));
        btnTopUp->setText(QCoreApplication::translate("MainWindow", "Top-Up Recommendations", nullptr));
        btnExport->setText(QCoreApplication::translate("MainWindow", "Exporter rapports", nullptr));
        btnLocations->setText(QCoreApplication::translate("MainWindow", "Gestion des emplacements", nullptr));
    } // retranslateUi

};

namespace Ui {
    class MainWindow: public Ui_MainWindow {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_MAINWINDOW_H
