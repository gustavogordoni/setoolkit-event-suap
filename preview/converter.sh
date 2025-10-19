#!/bin/bash

WORKDIR="$(pwd)"
JSONDIR="$WORKDIR/dados/json"
XMLDIR="$WORKDIR/dados/xml"

rm -r "$WORKDIR/dados/"

mkdir -p "$JSONDIR"
mkdir -p "$XMLDIR"

REPORT_DIR="/root/.set/reports"
LATEST_XML=$(sudo find "$REPORT_DIR" -maxdepth 1 -type f -name "*.xml" -printf "%T@ %p\n" | sort -nr | head -n1 | cut -d' ' -f2-)

if [ -z "$LATEST_XML" ]; then
    echo "Nenhum arquivo XML encontrado em $REPORT_DIR"
    exit 1
fi

sudo cp "$LATEST_XML" "$XMLDIR/input.xml"
sudo chown "$USER":"$USER" "$XMLDIR/input.xml"

XML_FILE="$XMLDIR/input.xml"
MAPPING_FILE="$WORKDIR/mapeamento.conf"

if [ ! -f "$MAPPING_FILE" ]; then
    echo "Arquivo de mapeamento.conf não encontrado!"
    exit 1
fi

echo "[" > "$JSONDIR/dados.json"

awk -v mapfile="$MAPPING_FILE" '
    BEGIN {
        # Carrega o mapeamento param -> JSON
        while ((getline < mapfile) > 0) {
            split($0, a, "=")
            map[a[1]] = a[2]
        }
    }
    /<url>/,/<\/url>/ {
        if ($0 ~ /<param>/) {
            gsub(/^[ \t]+/,"",$0)        # remove espaços no começo
            gsub(/<\/?param>/,"",$0)     # remove tags
            split($0,a,"=")
            key=a[1]
            value=a[2]
            for(i=3;i<=length(a);i++) value = value"="a[i] # junta se tiver '=' no valor
            gsub(/\+/," ",value)         # + vira espaço
            gsub(/^[ \t]+|[ \t]+$/,"",value) # remove espaços sobrando
            if (key in map) {
                data[map[key]]=value
            }
        }
        if ($0 ~ /<\/url>/) {
            # imprime JSON do bloco
            printf "{"
            first=1
            for (k in data) {
                if (!first) printf ", "
                printf "\"%s\": \"%s\"", k, data[k]
                first=0
            }
            print "},"
            delete data
        }
    }
' "$XML_FILE" >> "$JSONDIR/dados.json"


sed -i '$ s/},$/}/' "$JSONDIR/dados.json"
echo "]" >> "$JSONDIR/dados.json"

echo "JSON gerado em $JSONDIR/dados.json"
