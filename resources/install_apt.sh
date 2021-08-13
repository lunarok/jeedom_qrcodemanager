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
sudo apt-get -y install python3-dev python3-pip python3-setuptools libzbar0 zbar-tools

echo 20 > ${PROGRESS_FILE}
if [ $(pip3 list | grep segno | wc -l) -eq 0 ]; then
    echo "Installation du module segno pour python"
    sudo pip3 install segno
fi

echo 30 > ${PROGRESS_FILE}
if [ $(pip3 list | grep pyzbar | wc -l) -eq 0 ]; then
    echo "Installation du module pyzbar pour python"
    sudo pip3 install pyzbar
fi

echo 40 > ${PROGRESS_FILE}
if [ $(pip3 list | grep python-barcode | wc -l) -eq 0 ]; then
    echo "Installation du module python-barcode pour python"
    sudo pip3 install python-barcode
fi

echo 50 > ${PROGRESS_FILE}
if [ $(pip3 list | grep pillow | wc -l) -eq 0 ]; then
    echo "Installation du module pillow pour python"
    sudo pip3 install pillow
fi

echo 100 > /${PROGRESS_FILE}
echo "********************************************************"
echo "*             Installation terminée                    *"
echo "********************************************************"
rm ${PROGRESS_FILE}
