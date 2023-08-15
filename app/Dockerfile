# syntax=docker/dockerfile:1.4
FROM --platform=$BUILDPLATFORM python:latest AS builder

WORKDIR /app

COPY requirements.txt /app
RUN pip install --no-cache-dir -U pip wheel setuptools
RUN --mount=type=cache,target=/root/.cache/pip \
    pip install -r requirements.txt

# COPY . /app

EXPOSE 80

ENTRYPOINT ["python3"]
CMD ["app.py"]

FROM builder as dev-envs

RUN <<EOF
apk update
apk add git
EOF

RUN <<EOF
addgroup -S docker
adduser -S --shell /bin/bash --ingroup docker vscode
EOF
# install Docker tools (cli, buildx, compose)
COPY --from=gloursdocker/docker / /