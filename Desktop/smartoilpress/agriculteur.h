#ifndef AGRICULTEUR_H
#define AGRICULTEUR_H

#include <QString>

class Agriculteur
{
public:
    Agriculteur();
    Agriculteur(int id, const QString &cin, const QString &nom, 
                const QString &adresse, const QString &telephone, 
                const QString &email, const QString &superficie, 
                const QString &oliviers);

    int getId() const;
    QString getCin() const;
    QString getNom() const;
    QString getAdresse() const;
    QString getTelephone() const;
    QString getEmail() const;
    QString getSuperficie() const;
    QString getOliviers() const;

    void setId(int id);
    void setCin(const QString &cin);
    void setNom(const QString &nom);
    void setAdresse(const QString &adresse);
    void setTelephone(const QString &telephone);
    void setEmail(const QString &email);
    void setSuperficie(const QString &superficie);
    void setOliviers(const QString &oliviers);

private:
    int id;
    QString cin;
    QString nom;
    QString adresse;
    QString telephone;
    QString email;
    QString superficie;
    QString oliviers;
};

#endif // AGRICULTEUR_H
