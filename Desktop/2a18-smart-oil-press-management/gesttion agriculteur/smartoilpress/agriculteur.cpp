#include "agriculteur.h"

Agriculteur::Agriculteur()
    : id(0), cin(""), nom(""), adresse(""), telephone(""), 
      email(""), superficie(""), oliviers("")
{
}

Agriculteur::Agriculteur(int id, const QString &cin, const QString &nom,
                         const QString &adresse, const QString &telephone,
                         const QString &email, const QString &superficie,
                         const QString &oliviers)
    : id(id), cin(cin), nom(nom), adresse(adresse), telephone(telephone),
      email(email), superficie(superficie), oliviers(oliviers)
{
}

int Agriculteur::getId() const { return id; }
QString Agriculteur::getCin() const { return cin; }
QString Agriculteur::getNom() const { return nom; }
QString Agriculteur::getAdresse() const { return adresse; }
QString Agriculteur::getTelephone() const { return telephone; }
QString Agriculteur::getEmail() const { return email; }
QString Agriculteur::getSuperficie() const { return superficie; }
QString Agriculteur::getOliviers() const { return oliviers; }

void Agriculteur::setId(int id) { this->id = id; }
void Agriculteur::setCin(const QString &cin) { this->cin = cin; }
void Agriculteur::setNom(const QString &nom) { this->nom = nom; }
void Agriculteur::setAdresse(const QString &adresse) { this->adresse = adresse; }
void Agriculteur::setTelephone(const QString &telephone) { this->telephone = telephone; }
void Agriculteur::setEmail(const QString &email) { this->email = email; }
void Agriculteur::setSuperficie(const QString &superficie) { this->superficie = superficie; }
void Agriculteur::setOliviers(const QString &oliviers) { this->oliviers = oliviers; }
