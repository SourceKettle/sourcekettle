#!/bin/bash

APPDIR=$(dirname $0);
APPDIR=$(readlink -f "$APPDIR/../app");

$APPDIR/Console/cake -app $APPDIR git authorized_keys $1;
