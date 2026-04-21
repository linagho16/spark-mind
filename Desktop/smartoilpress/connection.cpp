#include "connection.h"

Connection::Connection()
{
    db = QSqlDatabase::addDatabase("QODBC");
    db.setDatabaseName("Source_projet2A18");
    db.setUserName("louay");
    db.setPassword("123456");
}

Connection& Connection::createInstance()
{
    static Connection instance;
    return instance;
}

bool Connection::connexion()
{
    if (db.open()) {
        QMessageBox::information(nullptr, "Connexion", "Connexion réussie !");
        return true;
    } else {
        QMessageBox::critical(nullptr, "Erreur", db.lastError().text());
        return false;
    }
}

QSqlDatabase Connection::getDB()
{
    return db;
}
