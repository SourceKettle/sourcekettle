#!/bin/bash

APPDIR=$(dirname $0);
APPDIR=$(readlink -f "$APPDIR/../app");

$APPDIR/Console/cake -app $APPDIR git authorizedKeys $1;
