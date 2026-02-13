#ifndef HISTORIQUE_H
#define HISTORIQUE_H

#include <QString>
#include <QDateTime>

class Historique
{
public:
    Historique();
    Historique(int id, int agriculteurId, const QString &action,
               const QString &details, const QDateTime &dateHeure,
               const QString &utilisateur);

    int getId() const;
    int getAgriculteurId() const;
    QString getAction() const;
    QString getDetails() const;
    QDateTime getDateHeure() const;
    QString getUtilisateur() const;

    void setId(int id);
    void setAgriculteurId(int id);
    void setAction(const QString &action);
    void setDetails(const QString &details);
    void setDateHeure(const QDateTime &dateHeure);
    void setUtilisateur(const QString &utilisateur);

private:
    int id;
    int agriculteurId;
    QString action;
    QString details;
    QDateTime dateHeure;
    QString utilisateur;
};

#endif // HISTORIQUE_H
