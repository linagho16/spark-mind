#include "culture.h"

Culture::Culture()
    : id(0), agriculteurId(0), nom(""), type(""), superficie(""),
      datePlantation(QDate()), variete(""), etat("")
{
}

Culture::Culture(int id, int agriculteurId, const QString &nom, const QString &type,
                 const QString &superficie, const QDate &datePlantation,
                 const QString &variete, const QString &etat)
    : id(id), agriculteurId(agriculteurId), nom(nom), type(type),
      superficie(superficie), datePlantation(datePlantation),
      variete(variete), etat(etat)
{
}

int Culture::getId() const { return id; }
int Culture::getAgriculteurId() const { return agriculteurId; }
QString Culture::getNom() const { return nom; }
QString Culture::getType() const { return type; }
QString Culture::getSuperficie() const { return superficie; }
QDate Culture::getDatePlantation() const { return datePlantation; }
QString Culture::getVariete() const { return variete; }
QString Culture::getEtat() const { return etat; }

void Culture::setId(int id) { this->id = id; }
void Culture::setAgriculteurId(int id) { this->agriculteurId = id; }
void Culture::setNom(const QString &nom) { this->nom = nom; }
void Culture::setType(const QString &type) { this->type = type; }
void Culture::setSuperficie(const QString &superficie) { this->superficie = superficie; }
void Culture::setDatePlantation(const QDate &date) { this->datePlantation = date; }
void Culture::setVariete(const QString &variete) { this->variete = variete; }
void Culture::setEtat(const QString &etat) { this->etat = etat; }
