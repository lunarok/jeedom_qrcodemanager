#!/bin/bash
PROGRESS_FILE=/tmp/dependancy_qrcodemanager_in_progress
if [ ! -z $1 ]; then
	PROGRESS_FILE=$1
fi
touch ${PROGRESS_FILE}
echo 0 > ${PROGRESS_FILE}
echo "********************************************************"
echo "*             Installation des dépendances             *"
echo "********************************************************"
sudo apt-get update
echo 10 > ${PROGRESS_FILE}
echo "Installation des dépendances apt"
sudo apt-get -y install python3-dev python3-pip python3-setuptools libzbar0 zbar-tools qrencode

echo 20 > ${PROGRESS_FILE}
echo "Installation du module cose pour python"
sudo pip3 install cose -U

echo 30 > ${PROGRESS_FILE}
echo "Installation du module pyzbar pour python"
sudo pip3 install pyzbar -U

echo 40 > ${PROGRESS_FILE}
echo "Installation du module python-jose[cryptography] pour python"
sudo pip3 install python-jose[cryptography] -U

echo 50 > ${PROGRESS_FILE}
echo "Installation du module pillow pour python"
sudo pip3 install pillow -U

echo 60 > ${PROGRESS_FILE}
echo "Installation du module cryptography pour python"
sudo pip3 install cryptography -U

echo 70 > ${PROGRESS_FILE}
echo "Installation du module base45 pour python"
sudo pip3 install base45 -U

echo 75 > ${PROGRESS_FILE}
echo "Installation du module cbor2 pour python"
sudo pip3 install cbor2 -U

echo 80 > ${PROGRESS_FILE}
echo "Installation du module lxml pour python"
sudo pip3 install lxml -U

echo 85 > ${PROGRESS_FILE}
echo "Installation du module python-dateutil pour python"
sudo pip3 install python-dateutil -U

echo 90 > ${PROGRESS_FILE}
echo "Installation du module asn1crypto pour python"
sudo pip3 install asn1crypto -U

echo 95 > ${PROGRESS_FILE}
echo "Installation du module jwcrypto pour python"
sudo pip3 install jwcrypto -U

echo 100 > /${PROGRESS_FILE}
echo "********************************************************"
echo "*             Installation terminée                    *"
echo "********************************************************"
rm ${PROGRESS_FILE}
