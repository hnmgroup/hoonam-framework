SCR_DIR="$(dirname $(realpath $0))"

docker run --rm -v $SCR_DIR:/app mamuz/phpda
