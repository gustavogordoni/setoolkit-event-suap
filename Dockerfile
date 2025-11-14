FROM python:3.11-slim

RUN apt update && \
    apt install -y git sudo python3-dev gcc && \
    rm -rf /var/lib/apt/lists/*

RUN pip install --no-cache-dir pexpect pycrypto pyopenssl requests

RUN git clone https://github.com/trustedsec/social-engineer-toolkit /opt/setoolkit && \
    cd /opt/setoolkit && \
    python3 setup.py install

ENTRYPOINT ["setoolkit"]