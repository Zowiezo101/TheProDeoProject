# We start out with a Python image
FROM python:3.12

WORKDIR /app

# install default-mysql-client for mysqldump
RUN apt-get update && apt-get install -y default-mysql-client

RUN pip install --upgrade pip
RUN pip install mysql-connector-python

# Without the -u, Docker waits for output to pile up before showing it in the terminal. 
# We want it right away
CMD ["python3", "-u", "main.py"]