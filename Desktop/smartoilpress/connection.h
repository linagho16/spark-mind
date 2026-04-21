#ifndef CONNECTION_H
#define CONNECTION_H

#include <QSqlDatabase>
#include <QSqlError>
#include <QMessageBox>

class Connection
{
public:
    static Connection& createInstance();
    bool connexion();
    QSqlDatabase getDB();

private:
    Connection();
    Connection(const Connection&) = delete;
    Connection& operator=(const Connection&) = delete;

    QSqlDatabase db;
};

#endif // CONNECTION_H
