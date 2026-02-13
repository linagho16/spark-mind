#include "historique.h"

Historique::Historique()
    : id(0), agriculteurId(0), action(""), details(""),
      dateHeure(QDateTime()), utilisateur("")
{
}

Historique::Historique(int id, int agriculteurId, const QString &action,
                       const QString &details, const QDateTime &dateHeure,
                       const QString &utilisateur)
    : id(id), agriculteurId(agriculteurId), action(action), details(details),
      dateHeure(dateHeure), utilisateur(utilisateur)
{
}

int Historique::getId() const { return id; }
int Historique::getAgriculteurId() const { return agriculteurId; }
QString Historique::getAction() const { return action; }
QString Historique::getDetails() const { return details; }
QDateTime Historique::getDateHeure() const { return dateHeure; }
QString Historique::getUtilisateur() const { return utilisateur; }

void Historique::setId(int id) { this->id = id; }
void Historique::setAgriculteurId(int id) { this->agriculteurId = id; }
void Historique::setAction(const QString &action) { this->action = action; }
void Historique::setDetails(const QString &details) { this->details = details; }
void Historique::setDateHeure(const QDateTime &dateHeure) { this->dateHeure = dateHeure; }
void Historique::setUtilisateur(const QString &utilisateur) { this->utilisateur = utilisateur; }
