#ifndef CULTURE_H
#define CULTURE_H

#include <QString>
#include <QDate>

class Culture
{
public:
    Culture();
    Culture(int id, int agriculteurId, const QString &nom, const QString &type,
            const QString &superficie, const QDate &datePlantation,
            const QString &variete, const QString &etat);

    int getId() const;
    int getAgriculteurId() const;
    QString getNom() const;
    QString getType() const;
    QString getSuperficie() const;
    QDate getDatePlantation() const;
    QString getVariete() const;
    QString getEtat() const;

    void setId(int id);
    void setAgriculteurId(int id);
    void setNom(const QString &nom);
    void setType(const QString &type);
    void setSuperficie(const QString &superficie);
    void setDatePlantation(const QDate &date);
    void setVariete(const QString &variete);
    void setEtat(const QString &etat);

private:
    int id;
    int agriculteurId;
    QString nom;
    QString type;
    QString superficie;
    QDate datePlantation;
    QString variete;
    QString etat;
};

#endif // CULTURE_H
