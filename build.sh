#!/bin/bash
WORKING_DIR=$(dirname $0)
VERSION=$(php -r "echo json_decode(file_get_contents('${WORKING_DIR}/plugin.json'), true)['currentVersion'];")
TEMP_DIR='/tmp/NetiToolKit/Core/NetiToolKit'
CURRENT_DIR=$(pwd)
if [ -f "${CURRENT_DIR}/NetiPurchaseHistory-${VERSION}.zip" ]; then
    rm "${CURRENT_DIR}/NetiPurchaseHistory-${VERSION}.zip"
fi
mkdir -p $TEMP_DIR
cp -Rp $WORKING_DIR/* $TEMP_DIR
rm "${TEMP_DIR}/build.sh"
if [ -f "${TEMP_DIR}/sftp-config.json" ]; then
    rm "${TEMP_DIR}/sftp-config.json"
fi
if [ -d "${TEMP_DIR}/nbproject/" ]; then
    rm "${TEMP_DIR}/nbproject/" -R
fi
cd $(dirname $(dirname $TEMP_DIR))
zip -qr "${CURRENT_DIR}/NetiToolKit-${VERSION}.zip" $(basename $(dirname $TEMP_DIR))
cd $CURRENT_DIR
echo "Package wurde erstellt unter ${CURRENT_DIR}/NetiToolKit-${VERSION}.zip"
rm -R $(dirname $(dirname $TEMP_DIR))
