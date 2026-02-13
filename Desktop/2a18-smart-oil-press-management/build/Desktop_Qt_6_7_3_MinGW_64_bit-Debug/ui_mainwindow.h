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
#include <QtWidgets/QDateEdit>
#include <QtWidgets/QFrame>
#include <QtWidgets/QHBoxLayout>
#include <QtWidgets/QHeaderView>
#include <QtWidgets/QLabel>
#include <QtWidgets/QLineEdit>
#include <QtWidgets/QMainWindow>
#include <QtWidgets/QPushButton>
#include <QtWidgets/QSpacerItem>
#include <QtWidgets/QStackedWidget>
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
    QStackedWidget *stackedModules;
    QWidget *pageExtraction;
    QVBoxLayout *contentLayout;
    QFrame *topBar;
    QHBoxLayout *topBarLayout;
    QLabel *appTitle;
    QLabel *userLabel;
    QFrame *card;
    QHBoxLayout *cardMainLayout;
    QVBoxLayout *leftContent;
    QLabel *cardTitle;
    QHBoxLayout *extActionsRow;
    QLabel *labelSearchExt;
    QLineEdit *searchExt;
    QPushButton *btnSearchExt;
    QLabel *labelSortExt;
    QComboBox *sortExt;
    QComboBox *sortOrderExt;
    QPushButton *btnSortExt;
    QPushButton *btnExportPdfExt;
    QPushButton *btnStatsExt;
    QTableWidget *table_ext;
    QFrame *formFrame;
    QVBoxLayout *formLayout;
    QLabel *l_titre;
    QLabel *l0;
    QLineEdit *id_ext;
    QLabel *l1;
    QDateEdit *date_ext;
    QLabel *l2;
    QComboBox *combo_type_ext;
    QLabel *l3;
    QLineEdit *citerne_ext;
    QPushButton *btn_valider_ext;
    QHBoxLayout *editDeleteRow;
    QPushButton *btn_modifier_ext;
    QPushButton *btn_supprimer_ext;
    QWidget *pageStock;
    QVBoxLayout *stockLayout;
    QFrame *topBarStock;
    QHBoxLayout *topBarStockLayout;
    QLabel *stockTitle;
    QSpacerItem *topStockSpacer;
    QLabel *stockUserLabel;
    QVBoxLayout *stockHostLayout;
    QWidget *pageCommande;
    QVBoxLayout *commandeLayout;
    QFrame *topBarCommande;
    QHBoxLayout *topBarCommandeLayout;
    QLabel *commandeTitle;
    QSpacerItem *topCommandeSpacer;
    QLabel *commandeUserLabel;
    QVBoxLayout *commandeHostLayout;
    QWidget *pageAgriculteur;
    QVBoxLayout *agriculteurLayout;
    QFrame *topBarAgriculteur;
    QHBoxLayout *topBarAgriculteurLayout;
    QLabel *agriculteurTitle;
    QSpacerItem *topAgriculteurSpacer;
    QLabel *agriculteurUserLabel;
    QVBoxLayout *agriculteurHostLayout;

    void setupUi(QMainWindow *MainWindow)
    {
        if (MainWindow->objectName().isEmpty())
            MainWindow->setObjectName("MainWindow");
        MainWindow->resize(1240, 750);
        centralwidget = new QWidget(MainWindow);
        centralwidget->setObjectName("centralwidget");
        centralwidget->setStyleSheet(QString::fromUtf8("/* STYLE GLOBAL - THEME NATURE & HUILE */\n"
"QWidget#centralwidget {\n"
"    background-color: #F4EEE4;\n"
"    font-family: \"Segoe UI\";\n"
"    font-size: 12px;\n"
"}\n"
"\n"
"/* --- BARRE DU HAUT --- */\n"
"QFrame#topBar,\n"
"QFrame#topBarCommande,\n"
"QFrame#topBarStock,\n"
"QFrame#topBarAgriculteur {\n"
"    background: qlineargradient(x1:0, y1:0, x2:1, y2:0, stop:0 #243C53, stop:1 #2B5978);\n"
"    border-radius: 10px;\n"
"}\n"
"QLabel#appTitle,\n"
"QLabel#commandeTitle,\n"
"QLabel#stockTitle,\n"
"QLabel#agriculteurTitle {\n"
"    color: #FFFFFF;\n"
"    font-size: 17px;\n"
"    font-weight: 700;\n"
"    padding: 0 6px;\n"
"}\n"
"QLabel#userLabel,\n"
"QLabel#commandeUserLabel,\n"
"QLabel#stockUserLabel,\n"
"QLabel#agriculteurUserLabel { color: #E6EEF4; font-size: 12px; }\n"
"\n"
"/* --- SIDEBAR (Style type capture) --- */\n"
"QFrame#sidebar {\n"
"    background: qlineargradient(x1:0, y1:0, x2:0, y2:1,\n"
"        stop:0 #2A3B4C, stop:1 #1E2B39);\n"
"    border-right: 1px solid #16202C;\n"
"}\n"
"QLabel"
                        "#sidebarTitle {\n"
"    color: #F5F5F5;\n"
"    font-weight: 700;\n"
"    font-size: 13px;\n"
"    margin: 8px 0 10px 0;\n"
"}\n"
"\n"
"QPushButton#navActive,\n"
"QPushButton#navButton_2,\n"
"QPushButton#navButton_3,\n"
"QPushButton#navButton_4,\n"
"QPushButton#navAccent {\n"
"    text-align: left;\n"
"    padding: 12px 16px;\n"
"    border-radius: 10px;\n"
"    font-weight: 600;\n"
"}\n"
"\n"
"QPushButton[navState=\"normal\"] {\n"
"    background-color: #314A67;\n"
"    color: #FFFFFF;\n"
"    border: 2px solid #24364D;\n"
"}\n"
"\n"
"QPushButton[navState=\"active\"] {\n"
"    background-color: #F2B705;\n"
"    color: #1F2937;\n"
"    border: 2px solid #C99305;\n"
"    font-weight: 700;\n"
"}\n"
"\n"
"/* --- CARTE CENTRALE --- */\n"
"QFrame#card {\n"
"    background-color: #FFFFFF;\n"
"    border: 1px solid #E3D8C7;\n"
"    border-radius: 12px;\n"
"}\n"
"QLabel#cardTitle { color: #245B7A; font-weight: 700; font-size: 16px; }\n"
"\n"
"/* --- TABLEAU (En Vert maintenant) --- */\n"
"QTableWidget {\n"
"    backgr"
                        "ound-color: #ffffff; gridline-color: #E3D8C7; selection-background-color: #FFE9B5; selection-color: #000000; border: 1px solid #E3D8C7;\n"
"}\n"
"QHeaderView::section {\n"
"    background-color: #2B5978; color: #FFFFFF; padding: 8px; border: none; font-weight: 600;\n"
"}\n"
"\n"
"/* --- FORMULAIRE --- */\n"
"/* Bordures vertes au lieu de bleues */\n"
"QLineEdit, QComboBox, QDateEdit, QSpinBox {\n"
"    background-color: #FFFBF5; border: 2px solid #2B5978; border-radius: 6px; padding: 6px; color: #333;\n"
"}\n"
"\n"
"/* Bouton Valider */\n"
"QPushButton#btn_valider_ext {\n"
"    background-color: #6B8E23; color: white; padding: 10px; border-radius: 6px; font-weight: bold; font-size: 13px;\n"
"}\n"
"QPushButton#btn_valider_ext:hover { background-color: #5A7A1E; }\n"
"\n"
"QPushButton#btn_modifier_ext {\n"
"    background-color: #6B8E23;\n"
"    color: #FFFFFF;\n"
"    padding: 10px;\n"
"    border-radius: 6px;\n"
"    font-weight: bold;\n"
"    font-size: 13px;\n"
"}\n"
"QPushButton#btn_modifier_ext:hover { back"
                        "ground-color: #5A7A1E; }\n"
"QPushButton#btn_supprimer_ext {\n"
"    background-color: #C62828;\n"
"    color: #FFFFFF;\n"
"    padding: 10px;\n"
"    border-radius: 6px;\n"
"    font-weight: bold;\n"
"    font-size: 13px;\n"
"}\n"
"QPushButton#btn_supprimer_ext:hover { background-color: #B71C1C; }\n"
"\n"
"QPushButton#btnSortExt,\n"
"QPushButton#btnSearchExt,\n"
"QPushButton#btnStatsExt {\n"
"    background-color: #6B8E23;\n"
"    color: #FFFFFF;\n"
"    padding: 8px 12px;\n"
"    border-radius: 6px;\n"
"    font-weight: 600;\n"
"}\n"
"QPushButton#btnExportPdfExt {\n"
"    background-color: #F2B705;\n"
"    color: #2B2B2B;\n"
"    padding: 8px 12px;\n"
"    border-radius: 6px;\n"
"    font-weight: 600;\n"
"}\n"
"\n"
"QPushButton#btnSortExt:hover,\n"
"QPushButton#btnSearchExt:hover,\n"
"QPushButton#btnStatsExt:hover {\n"
"    background-color: #5A7A1E;\n"
"}\n"
"QPushButton#btnExportPdfExt:hover { background-color: #E0A800; }\n"
"\n"
"QPushButton:hover {\n"
"    background-color: #F2B705;\n"
"    color: #FFFFF"
                        "F;\n"
"}\n"
""));
        mainLayout = new QHBoxLayout(centralwidget);
        mainLayout->setSpacing(0);
        mainLayout->setObjectName("mainLayout");
        mainLayout->setContentsMargins(0, 0, 0, 0);
        sidebar = new QFrame(centralwidget);
        sidebar->setObjectName("sidebar");
        sidebar->setMinimumSize(QSize(210, 0));
        sidebarLayout = new QVBoxLayout(sidebar);
        sidebarLayout->setSpacing(10);
        sidebarLayout->setObjectName("sidebarLayout");
        sidebarLayout->setContentsMargins(12, 12, 12, 12);
        sidebarTitle = new QLabel(sidebar);
        sidebarTitle->setObjectName("sidebarTitle");

        sidebarLayout->addWidget(sidebarTitle);

        navActive = new QPushButton(sidebar);
        navActive->setObjectName("navActive");
        navActive->setMinimumSize(QSize(160, 64));
        navActive->setMaximumSize(QSize(220, 70));

        sidebarLayout->addWidget(navActive);

        navButton_2 = new QPushButton(sidebar);
        navButton_2->setObjectName("navButton_2");
        navButton_2->setMinimumSize(QSize(160, 64));
        navButton_2->setMaximumSize(QSize(220, 70));

        sidebarLayout->addWidget(navButton_2);

        navButton_3 = new QPushButton(sidebar);
        navButton_3->setObjectName("navButton_3");
        navButton_3->setMinimumSize(QSize(160, 64));
        navButton_3->setMaximumSize(QSize(220, 70));

        sidebarLayout->addWidget(navButton_3);

        navButton_4 = new QPushButton(sidebar);
        navButton_4->setObjectName("navButton_4");
        navButton_4->setMinimumSize(QSize(160, 64));
        navButton_4->setMaximumSize(QSize(220, 70));

        sidebarLayout->addWidget(navButton_4);

        navAccent = new QPushButton(sidebar);
        navAccent->setObjectName("navAccent");
        navAccent->setMinimumSize(QSize(160, 64));
        navAccent->setMaximumSize(QSize(220, 70));

        sidebarLayout->addWidget(navAccent);


        mainLayout->addWidget(sidebar);

        stackedModules = new QStackedWidget(centralwidget);
        stackedModules->setObjectName("stackedModules");
        pageExtraction = new QWidget();
        pageExtraction->setObjectName("pageExtraction");
        contentLayout = new QVBoxLayout(pageExtraction);
        contentLayout->setObjectName("contentLayout");
        contentLayout->setContentsMargins(20, 20, 20, 20);
        topBar = new QFrame(pageExtraction);
        topBar->setObjectName("topBar");
        topBar->setMinimumSize(QSize(0, 60));
        topBar->setMaximumSize(QSize(16777215, 60));
        topBarLayout = new QHBoxLayout(topBar);
        topBarLayout->setObjectName("topBarLayout");
        appTitle = new QLabel(topBar);
        appTitle->setObjectName("appTitle");
        appTitle->setAlignment(Qt::AlignmentFlag::AlignLeading|Qt::AlignmentFlag::AlignLeft|Qt::AlignmentFlag::AlignVCenter);

        topBarLayout->addWidget(appTitle);

        userLabel = new QLabel(topBar);
        userLabel->setObjectName("userLabel");

        topBarLayout->addWidget(userLabel);


        contentLayout->addWidget(topBar);

        card = new QFrame(pageExtraction);
        card->setObjectName("card");
        cardMainLayout = new QHBoxLayout(card);
        cardMainLayout->setObjectName("cardMainLayout");
        leftContent = new QVBoxLayout();
        leftContent->setSpacing(8);
        leftContent->setObjectName("leftContent");
        leftContent->setContentsMargins(-1, -1, -1, 0);
        cardTitle = new QLabel(card);
        cardTitle->setObjectName("cardTitle");

        leftContent->addWidget(cardTitle);

        extActionsRow = new QHBoxLayout();
        extActionsRow->setSpacing(8);
        extActionsRow->setObjectName("extActionsRow");
        labelSearchExt = new QLabel(card);
        labelSearchExt->setObjectName("labelSearchExt");

        extActionsRow->addWidget(labelSearchExt);

        searchExt = new QLineEdit(card);
        searchExt->setObjectName("searchExt");

        extActionsRow->addWidget(searchExt);

        btnSearchExt = new QPushButton(card);
        btnSearchExt->setObjectName("btnSearchExt");

        extActionsRow->addWidget(btnSearchExt);

        labelSortExt = new QLabel(card);
        labelSortExt->setObjectName("labelSortExt");

        extActionsRow->addWidget(labelSortExt);

        sortExt = new QComboBox(card);
        sortExt->addItem(QString());
        sortExt->addItem(QString());
        sortExt->addItem(QString());
        sortExt->setObjectName("sortExt");

        extActionsRow->addWidget(sortExt);

        sortOrderExt = new QComboBox(card);
        sortOrderExt->addItem(QString());
        sortOrderExt->addItem(QString());
        sortOrderExt->setObjectName("sortOrderExt");

        extActionsRow->addWidget(sortOrderExt);

        btnSortExt = new QPushButton(card);
        btnSortExt->setObjectName("btnSortExt");

        extActionsRow->addWidget(btnSortExt);

        btnExportPdfExt = new QPushButton(card);
        btnExportPdfExt->setObjectName("btnExportPdfExt");

        extActionsRow->addWidget(btnExportPdfExt);

        btnStatsExt = new QPushButton(card);
        btnStatsExt->setObjectName("btnStatsExt");

        extActionsRow->addWidget(btnStatsExt);


        leftContent->addLayout(extActionsRow);

        table_ext = new QTableWidget(card);
        if (table_ext->columnCount() < 4)
            table_ext->setColumnCount(4);
        QTableWidgetItem *__qtablewidgetitem = new QTableWidgetItem();
        table_ext->setHorizontalHeaderItem(0, __qtablewidgetitem);
        QTableWidgetItem *__qtablewidgetitem1 = new QTableWidgetItem();
        table_ext->setHorizontalHeaderItem(1, __qtablewidgetitem1);
        QTableWidgetItem *__qtablewidgetitem2 = new QTableWidgetItem();
        table_ext->setHorizontalHeaderItem(2, __qtablewidgetitem2);
        QTableWidgetItem *__qtablewidgetitem3 = new QTableWidgetItem();
        table_ext->setHorizontalHeaderItem(3, __qtablewidgetitem3);
        table_ext->setObjectName("table_ext");
        table_ext->setRowCount(0);
        table_ext->setColumnCount(4);

        leftContent->addWidget(table_ext);


        cardMainLayout->addLayout(leftContent);

        formFrame = new QFrame(card);
        formFrame->setObjectName("formFrame");
        formFrame->setMaximumSize(QSize(300, 16777215));
        formLayout = new QVBoxLayout(formFrame);
        formLayout->setSpacing(8);
        formLayout->setObjectName("formLayout");
        l_titre = new QLabel(formFrame);
        l_titre->setObjectName("l_titre");
        l_titre->setAlignment(Qt::AlignmentFlag::AlignCenter);

        formLayout->addWidget(l_titre);

        l0 = new QLabel(formFrame);
        l0->setObjectName("l0");

        formLayout->addWidget(l0);

        id_ext = new QLineEdit(formFrame);
        id_ext->setObjectName("id_ext");

        formLayout->addWidget(id_ext);

        l1 = new QLabel(formFrame);
        l1->setObjectName("l1");

        formLayout->addWidget(l1);

        date_ext = new QDateEdit(formFrame);
        date_ext->setObjectName("date_ext");
        date_ext->setCalendarPopup(true);

        formLayout->addWidget(date_ext);

        l2 = new QLabel(formFrame);
        l2->setObjectName("l2");

        formLayout->addWidget(l2);

        combo_type_ext = new QComboBox(formFrame);
        combo_type_ext->addItem(QString());
        combo_type_ext->addItem(QString());
        combo_type_ext->addItem(QString());
        combo_type_ext->setObjectName("combo_type_ext");

        formLayout->addWidget(combo_type_ext);

        l3 = new QLabel(formFrame);
        l3->setObjectName("l3");

        formLayout->addWidget(l3);

        citerne_ext = new QLineEdit(formFrame);
        citerne_ext->setObjectName("citerne_ext");

        formLayout->addWidget(citerne_ext);

        btn_valider_ext = new QPushButton(formFrame);
        btn_valider_ext->setObjectName("btn_valider_ext");

        formLayout->addWidget(btn_valider_ext);

        editDeleteRow = new QHBoxLayout();
        editDeleteRow->setSpacing(8);
        editDeleteRow->setObjectName("editDeleteRow");
        btn_modifier_ext = new QPushButton(formFrame);
        btn_modifier_ext->setObjectName("btn_modifier_ext");

        editDeleteRow->addWidget(btn_modifier_ext);

        btn_supprimer_ext = new QPushButton(formFrame);
        btn_supprimer_ext->setObjectName("btn_supprimer_ext");

        editDeleteRow->addWidget(btn_supprimer_ext);


        formLayout->addLayout(editDeleteRow);


        cardMainLayout->addWidget(formFrame);


        contentLayout->addWidget(card);

        stackedModules->addWidget(pageExtraction);
        pageStock = new QWidget();
        pageStock->setObjectName("pageStock");
        stockLayout = new QVBoxLayout(pageStock);
        stockLayout->setObjectName("stockLayout");
        stockLayout->setContentsMargins(20, 20, 20, 20);
        topBarStock = new QFrame(pageStock);
        topBarStock->setObjectName("topBarStock");
        topBarStock->setMinimumSize(QSize(0, 60));
        topBarStock->setMaximumSize(QSize(16777215, 60));
        topBarStockLayout = new QHBoxLayout(topBarStock);
        topBarStockLayout->setObjectName("topBarStockLayout");
        stockTitle = new QLabel(topBarStock);
        stockTitle->setObjectName("stockTitle");
        stockTitle->setAlignment(Qt::AlignmentFlag::AlignLeading|Qt::AlignmentFlag::AlignLeft|Qt::AlignmentFlag::AlignVCenter);

        topBarStockLayout->addWidget(stockTitle);

        topStockSpacer = new QSpacerItem(0, 0, QSizePolicy::Policy::Expanding, QSizePolicy::Policy::Minimum);

        topBarStockLayout->addItem(topStockSpacer);

        stockUserLabel = new QLabel(topBarStock);
        stockUserLabel->setObjectName("stockUserLabel");

        topBarStockLayout->addWidget(stockUserLabel);


        stockLayout->addWidget(topBarStock);

        stockHostLayout = new QVBoxLayout();
        stockHostLayout->setObjectName("stockHostLayout");
        stockHostLayout->setContentsMargins(0, 0, 0, 0);

        stockLayout->addLayout(stockHostLayout);

        stackedModules->addWidget(pageStock);
        pageCommande = new QWidget();
        pageCommande->setObjectName("pageCommande");
        commandeLayout = new QVBoxLayout(pageCommande);
        commandeLayout->setObjectName("commandeLayout");
        commandeLayout->setContentsMargins(20, 20, 20, 20);
        topBarCommande = new QFrame(pageCommande);
        topBarCommande->setObjectName("topBarCommande");
        topBarCommande->setMinimumSize(QSize(0, 60));
        topBarCommande->setMaximumSize(QSize(16777215, 60));
        topBarCommandeLayout = new QHBoxLayout(topBarCommande);
        topBarCommandeLayout->setObjectName("topBarCommandeLayout");
        commandeTitle = new QLabel(topBarCommande);
        commandeTitle->setObjectName("commandeTitle");
        commandeTitle->setAlignment(Qt::AlignmentFlag::AlignLeading|Qt::AlignmentFlag::AlignLeft|Qt::AlignmentFlag::AlignVCenter);

        topBarCommandeLayout->addWidget(commandeTitle);

        topCommandeSpacer = new QSpacerItem(0, 0, QSizePolicy::Policy::Expanding, QSizePolicy::Policy::Minimum);

        topBarCommandeLayout->addItem(topCommandeSpacer);

        commandeUserLabel = new QLabel(topBarCommande);
        commandeUserLabel->setObjectName("commandeUserLabel");

        topBarCommandeLayout->addWidget(commandeUserLabel);


        commandeLayout->addWidget(topBarCommande);

        commandeHostLayout = new QVBoxLayout();
        commandeHostLayout->setObjectName("commandeHostLayout");
        commandeHostLayout->setContentsMargins(0, 0, 0, 0);

        commandeLayout->addLayout(commandeHostLayout);

        stackedModules->addWidget(pageCommande);
        pageAgriculteur = new QWidget();
        pageAgriculteur->setObjectName("pageAgriculteur");
        agriculteurLayout = new QVBoxLayout(pageAgriculteur);
        agriculteurLayout->setObjectName("agriculteurLayout");
        agriculteurLayout->setContentsMargins(20, 20, 20, 20);
        topBarAgriculteur = new QFrame(pageAgriculteur);
        topBarAgriculteur->setObjectName("topBarAgriculteur");
        topBarAgriculteur->setMinimumSize(QSize(0, 60));
        topBarAgriculteur->setMaximumSize(QSize(16777215, 60));
        topBarAgriculteurLayout = new QHBoxLayout(topBarAgriculteur);
        topBarAgriculteurLayout->setObjectName("topBarAgriculteurLayout");
        agriculteurTitle = new QLabel(topBarAgriculteur);
        agriculteurTitle->setObjectName("agriculteurTitle");
        agriculteurTitle->setAlignment(Qt::AlignmentFlag::AlignLeading|Qt::AlignmentFlag::AlignLeft|Qt::AlignmentFlag::AlignVCenter);

        topBarAgriculteurLayout->addWidget(agriculteurTitle);

        topAgriculteurSpacer = new QSpacerItem(0, 0, QSizePolicy::Policy::Expanding, QSizePolicy::Policy::Minimum);

        topBarAgriculteurLayout->addItem(topAgriculteurSpacer);

        agriculteurUserLabel = new QLabel(topBarAgriculteur);
        agriculteurUserLabel->setObjectName("agriculteurUserLabel");

        topBarAgriculteurLayout->addWidget(agriculteurUserLabel);


        agriculteurLayout->addWidget(topBarAgriculteur);

        agriculteurHostLayout = new QVBoxLayout();
        agriculteurHostLayout->setObjectName("agriculteurHostLayout");
        agriculteurHostLayout->setContentsMargins(0, 0, 0, 0);

        agriculteurLayout->addLayout(agriculteurHostLayout);

        stackedModules->addWidget(pageAgriculteur);

        mainLayout->addWidget(stackedModules);

        MainWindow->setCentralWidget(centralwidget);

        retranslateUi(MainWindow);

        stackedModules->setCurrentIndex(3);


        QMetaObject::connectSlotsByName(MainWindow);
    } // setupUi

    void retranslateUi(QMainWindow *MainWindow)
    {
        MainWindow->setWindowTitle(QCoreApplication::translate("MainWindow", "Smart Oil Press - Extraction", nullptr));
        sidebarTitle->setText(QCoreApplication::translate("MainWindow", "Smart Oil Press", nullptr));
        navActive->setText(QCoreApplication::translate("MainWindow", "Employ\303\251", nullptr));
        navButton_2->setText(QCoreApplication::translate("MainWindow", "Agriculteur", nullptr));
        navButton_3->setText(QCoreApplication::translate("MainWindow", "Stock", nullptr));
        navButton_4->setText(QCoreApplication::translate("MainWindow", "Extraction", nullptr));
        navAccent->setText(QCoreApplication::translate("MainWindow", "Commande", nullptr));
        appTitle->setText(QCoreApplication::translate("MainWindow", "EXTRACTION", nullptr));
        userLabel->setText(QCoreApplication::translate("MainWindow", "Connect\303\251 : Op\303\251rateur Production", nullptr));
        cardTitle->setText(QCoreApplication::translate("MainWindow", "Historique des Productions", nullptr));
        labelSearchExt->setText(QCoreApplication::translate("MainWindow", "Recherche", nullptr));
        searchExt->setPlaceholderText(QCoreApplication::translate("MainWindow", "Rechercher...", nullptr));
        btnSearchExt->setText(QCoreApplication::translate("MainWindow", "OK", nullptr));
        labelSortExt->setText(QCoreApplication::translate("MainWindow", "Trier", nullptr));
        sortExt->setItemText(0, QCoreApplication::translate("MainWindow", "ID", nullptr));
        sortExt->setItemText(1, QCoreApplication::translate("MainWindow", "Date", nullptr));
        sortExt->setItemText(2, QCoreApplication::translate("MainWindow", "N\302\260 Citerne", nullptr));

        sortOrderExt->setItemText(0, QCoreApplication::translate("MainWindow", "\342\206\221 Croissant", nullptr));
        sortOrderExt->setItemText(1, QCoreApplication::translate("MainWindow", "\342\206\223 D\303\251croissant", nullptr));

        btnSortExt->setText(QCoreApplication::translate("MainWindow", "Trier", nullptr));
        btnExportPdfExt->setText(QCoreApplication::translate("MainWindow", "Exporter PDF", nullptr));
        btnStatsExt->setText(QCoreApplication::translate("MainWindow", "Statistiques", nullptr));
        QTableWidgetItem *___qtablewidgetitem = table_ext->horizontalHeaderItem(0);
        ___qtablewidgetitem->setText(QCoreApplication::translate("MainWindow", "ID", nullptr));
        QTableWidgetItem *___qtablewidgetitem1 = table_ext->horizontalHeaderItem(1);
        ___qtablewidgetitem1->setText(QCoreApplication::translate("MainWindow", "Date", nullptr));
        QTableWidgetItem *___qtablewidgetitem2 = table_ext->horizontalHeaderItem(2);
        ___qtablewidgetitem2->setText(QCoreApplication::translate("MainWindow", "Type d'Huile", nullptr));
        QTableWidgetItem *___qtablewidgetitem3 = table_ext->horizontalHeaderItem(3);
        ___qtablewidgetitem3->setText(QCoreApplication::translate("MainWindow", "N\302\260 Citerne", nullptr));
        l_titre->setStyleSheet(QCoreApplication::translate("MainWindow", "color:#245B7A; font-weight:bold; font-size:14px;", nullptr));
        l_titre->setText(QCoreApplication::translate("MainWindow", "AJOUTER PRODUCTION", nullptr));
        l0->setText(QCoreApplication::translate("MainWindow", "ID :", nullptr));
        id_ext->setPlaceholderText(QCoreApplication::translate("MainWindow", "ID Extraction", nullptr));
        l1->setText(QCoreApplication::translate("MainWindow", "Date :", nullptr));
        l2->setText(QCoreApplication::translate("MainWindow", "Type d'Huile :", nullptr));
        combo_type_ext->setItemText(0, QCoreApplication::translate("MainWindow", "Huile d'Olive", nullptr));
        combo_type_ext->setItemText(1, QCoreApplication::translate("MainWindow", "Huile de Tournesol", nullptr));
        combo_type_ext->setItemText(2, QCoreApplication::translate("MainWindow", "Huile de Colza", nullptr));

        l3->setText(QCoreApplication::translate("MainWindow", "Num\303\251ro de citerne :", nullptr));
        citerne_ext->setPlaceholderText(QCoreApplication::translate("MainWindow", "N\302\260 citerne (1-50)", nullptr));
        btn_valider_ext->setText(QCoreApplication::translate("MainWindow", "AJOUTER", nullptr));
        btn_modifier_ext->setText(QCoreApplication::translate("MainWindow", "MODIFIER", nullptr));
        btn_supprimer_ext->setText(QCoreApplication::translate("MainWindow", "SUPPRIMER", nullptr));
        stockTitle->setText(QCoreApplication::translate("MainWindow", "STOCK", nullptr));
        stockUserLabel->setText(QCoreApplication::translate("MainWindow", "Connect\303\251 : Op\303\251rateur Production", nullptr));
        commandeTitle->setText(QCoreApplication::translate("MainWindow", "COMMANDE", nullptr));
        commandeUserLabel->setText(QCoreApplication::translate("MainWindow", "Connect\303\251 : Op\303\251rateur Production", nullptr));
        agriculteurTitle->setText(QCoreApplication::translate("MainWindow", "AGRICULTEUR", nullptr));
        agriculteurUserLabel->setText(QCoreApplication::translate("MainWindow", "Connect\303\251 : Op\303\251rateur Production", nullptr));
    } // retranslateUi

};

namespace Ui {
    class MainWindow: public Ui_MainWindow {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_MAINWINDOW_H
