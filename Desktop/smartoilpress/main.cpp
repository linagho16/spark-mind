#include "mainwindow.h"
#include "connection.h"
#include <QApplication>
#include <QLocale>
#include <QTranslator>

int main(int argc, char *argv[])
{
    QApplication a(argc, argv);

    QTranslator translator;
    const QStringList uiLanguages = QLocale::system().uiLanguages();
    for (const QString &locale : uiLanguages) {
        const QString baseName = "smartoilpress_" + QLocale(locale).name();
        if (translator.load(":/i18n/" + baseName)) {
            a.installTranslator(&translator);
            break;
        }
    }
    Connection& conn = Connection::createInstance();
    if (!conn.connexion()) {
        return -1;
    }
    MainWindow w;
    w.show();
    return a.exec();
}
