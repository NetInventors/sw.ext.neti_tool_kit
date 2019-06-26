#!/bin/bash

SCRIPT_VERSION='3.1.0'
CURRENT_DIR=$(pwd)
WORKING_DIR=$(cd $(dirname $0) && pwd)
BUILD_DIR="${WORKING_DIR}/.build"
PLUGIN_DIR="${BUILD_DIR}/plugins"
TEMP_DIR="${BUILD_DIR}/tmp"
UPDATE=1
PLUGIN_NAME=""
PLUGINS_BUILD=()
PLUGINS_DIST=()
TARGET_DIR=${CURRENT_DIR}

usage() { 
    echo "Usage: $0 [-h] [-n] [-t <string>]" 1>&2;
    exit 1; 
}

while getopts "hnt:" opt; do
    case "${opt}" in
        h)
            usage
            ;;
        n)
            UPDATE=0
            ;;
        t)
            TARGET_DIR=${OPTARG}
            ;;
        *)
            usage
            ;;
    esac
done
shift $((OPTIND-1))

if [[ "/" != "${TARGET_DIR:0:1}" ]]; then
    TARGET_DIR="${WORKING_DIR}/${TARGET_DIR}"
fi

if [[ ! -d $TARGET_DIR ]]; then
    echo "ERROR: Target dir doesn't exist"
    exit 2
fi

if [[ ! -w $TARGET_DIR ]]; then
    echo "ERROR: Can't write to target dir"
    exit 2
fi

TARGET_DIR=$(cd "${TARGET_DIR}" && pwd)

cleanup() {
    echo "INFO: Cleanup ${1}"
    rm -Rf ${1}
}

selfUpdate() {
    echo "INFO: Run self update process"
    UPDATE_DIR="${BUILD_DIR}/update"

    git clone --quiet ssh://git@gitlab.netinventors.de:2202/shopware/build-script.git ${UPDATE_DIR}

    cp -Rp ${UPDATE_DIR}/.build/plugins ${WORKING_DIR}/.build
    cp -p ${UPDATE_DIR}/build.sh ${WORKING_DIR}

    cleanup ${UPDATE_DIR}
}

runPlugins() {
    if [[ -z ${@} ]]; then
        return 0
    fi

    for PLUGIN in "${@}"; do
        EXECUTABLE="${PLUGIN_DIR}/${PLUGIN}.sh"

        if [[ -r "${EXECUTABLE}" ]]; then
            echo "INFO: Run plugin ${PLUGIN}"

            source "${EXECUTABLE}"

            __FN="__$(sed -r 's/(\-)([a-z])/\U\2/g' <<< "${PLUGIN}")"

            ${__FN}

            __RETURN=$?
            if [[ 2 -eq ${__RETURN} ]]; then
                echo "ERROR: A plugin stops the build process"

                cleanup ${TEMP_DIR}

                exit 2
            fi
        else
            echo "WARNING: Plugin executable ${EXECUTABLE} not found"
        fi
    done
}

build() {
    mkdir -p ${BUILD_DIR}

    if [[ 1 -eq ${UPDATE} ]]; then
        selfUpdate
        bash -c "$0 -n -t \"${TARGET_DIR}\""
        exit $?
    fi

    echo "INFO: Start build script version ${SCRIPT_VERSION}"

    if [[ ! -r "${BUILD_DIR}/build.cfg" ]]; then
        echo "ERROR: Unable to load build configuration file ${BUILD_DIR}/build.cfg"
        exit 2
    fi

    # Load build configuration file
    source "${BUILD_DIR}/build.cfg"

    if [[ -z "${PLUGIN_NAME}" ]]; then
        echo 'ERROR: Unable to resolve plugin name'
        exit 2
    fi

    if [[ ! -r "${WORKING_DIR}/plugin.xml" ]]; then
        echo "ERROR: Plugin describing file ${WORKING_DIR}/plugin.xml not found"
        exit 2
    fi

    DIST_DIR="${BUILD_DIR}/tmp/${PLUGIN_NAME}"
    VERSION=$(php -r "echo preg_replace('/.*<version>([^<]+)<\/version>.*/ims', '\\1', file_get_contents('${WORKING_DIR}/plugin.xml'), 1);")
    EXCLUDES=( ".build" ".idea" "build.sh" ".git" ".gitignore" "${EXCLUDES[@]}" )
    ZIP_FILE="${TARGET_DIR}/${PLUGIN_NAME}-${VERSION}.zip"

    if [[ -z "$VERSION" ]]; then
        echo 'ERROR: Unable to resolve plugin version from plugin.xml'
        exit 2
    fi

    # Remove existing package file
    if [[ -w "${ZIP_FILE}" ]]; then
        rm "${ZIP_FILE}"
    fi

    if [[ -f "${ZIP_FILE}" ]]; then
        echo "ERROR: Cannot override existing build file ${ZIP_FILE}"
        exit 2
    fi

    RSYNC_EXCLUDES=( --exclude="${PLUGIN_NAME}-*.zip" )

    cd ${WORKING_DIR}

    runPlugins "${PLUGINS_BUILD[@]}"

    for EXCLUDE in "${EXCLUDES[@]}"; do
        RSYNC_EXCLUDES+=( --exclude="${EXCLUDE}" )
    done

    # Remove old temp dir
    if [[ -d ${TEMP_DIR} ]]; then
        rm -Rf ${TEMP_DIR}
    fi

    # Create temporary build folder
    mkdir -p ${DIST_DIR}

    # Copy all files from source folder to temporary folder
    rsync -avzq "${RSYNC_EXCLUDES[@]}" . ${DIST_DIR}

    # Step into the temporary build folder
    cd ${DIST_DIR}

    runPlugins "${PLUGINS_DIST[@]}"

    # Replace __SECRET__ in plugin bootstrap to detect manipulations
    SECRET=$(head -c 1000 /dev/urandom | tr -dc 'A-Za-z0-9%,!_;:#@' | fold -w 32 | head -n 1)
    sed -i "s/__SECRET__/${SECRET}/g" "${PLUGIN_NAME}.php"

    # Create md5.json file including the md5 checksum of every file in the package and the plugin __SECRET__
    echo "<?php return [" > md5checksum.php
    for FILE in $(find -type f -printf '%P\n'); do
        echo "'${FILE}' => '$(md5sum ${FILE} | awk '{ print $1 }')'," >> md5checksum.php
    done;
    echo "'__SECRET__' => '${SECRET}'," >> md5checksum.php
    echo "];" >> md5checksum.php

    cd ${TEMP_DIR}

    # Zip temporary folder contents to package file in plugin folder
    zip -qr "${ZIP_FILE}" $(basename ${DIST_DIR})

    echo "INFO: Created build package is located in ${ZIP_FILE}"

    cleanup ${TEMP_DIR}

    cd ${CURRENT_DIR}
}

build
