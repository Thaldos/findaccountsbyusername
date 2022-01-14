# find-accounts-by-username

## Installation
### Install vendor
    composer install

### Install Sherlock project in src
    cd src
    git clone https://github.com/sherlock-project/sherlock.git
    cd sherlock
    python3 -m pip install -r requirements.txt

<br>

### Disable the file output:

In `src\sherlock\sherlock\sherlock.py` remove lines :

    with open(result_file, "w", encoding="utf-8") as file:
            exists_counter = 0
            for website_name in results:
                dictionary = results[website_name]
                if dictionary.get("status").status == QueryStatus.CLAIMED:
                    exists_counter += 1
                    file.write(dictionary["url_user"] + "\n")
            file.write(f"Total Websites Username Detected On : {exists_counter}\n")

## Resolved error 
Error message : 

    /usr/bin/python3: No module named pip

Cause : 

    python3 -m pip install -r requirements.txt

Solution :

    sudo apt-get update
    sudo apt-get install -y python3-pip

<br>
<hr>
<br>

Error message :

    Caused by SSLError(SSLCertVerificationError(1, '[SSL: CERTIFICATE_VERIFY_FAILED] certificate verify failed: unable to get local issuer certificate ...
    
Cause :

    python3 sherlock Thaldos


Solution :

    src\sherlock\sherlock\sites.py, line 114 set :             
        data_file_path = "/shared/httpd/findaccountsbyusername/src/sherlock/sherlock/resources/data.json"