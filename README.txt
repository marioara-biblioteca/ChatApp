- mai intai cream arhitectura din lab9 unind cele 2 masini intr-un cluster si enbland dns, prometherus, metrics server, registry
- instalam docker cu scriptul installDocker.sh (lab7)
- am urmarit tutorialul pentru a crea deploymentul de wordpress cu mysql https://kubernetes.io/docs/tutorials/stateful-application/mysql-wordpress-persistent-volume/
(am realizat ulterior ca nu trebuia cu persistent volumes pentru ca noi nu vrem sa " PersistentVolumes and PersistentVolumeClaims are independent from Pod lifecycles and preserve data through restarting, rescheduling, and even deleting Pods"
asa ca stergem partile respective din yaml-uri 
- cream registry-ul privat. Un fel de Docker Hub privat pe portul 32000. Îl vom folosi pentru a putea crea imagini Docker și a le putea distribui tuturor nodurilor din cluster.
- am descarcat imaginile de php-apache si couchdb si am pornit containere pentru a face aplicatia de chat
- am lucat in containerul de php apache direct (am setat sa expuna serviciul pe portul 88 si ma conectam cu adr_ip_pub_kube1:88 pentru a testa)
- containerul de couchdb asculta pe portul default 5984 si am setat sa fie expus pe external ip ip_privat_kube1
- aplicatia de chat porneste din chatapp/php/config.php unde cream baza de date si implementam functiile pe baza de curl catre couchdb. Aplicatia permite signup-ul userilor , display-ul userilor logati in timp real si chat-ul in timp real. Identificarea mesajelor conversatiei se face pe baza unui id de sesiune aferent fiecarui user(https://www.codingnepalweb.com/chat-web-application-using-php/)
- stilizam pagina de wordpress putin si adaugam un iframe care "pointeaza" codul nostru pentru aplicatia de chat expusa din containerul de php-apache expus pe portul 88
- acum trebuie sa salvam fisierle din POD-ul de wordpress si sa facem dump local la baza de date mysql folosita de wordpress pentru a le putea incarca in deploymentul de wordpress ori de cate ori vrem (oricare din ele)

//https://gist.github.com/devdrops/c59ee84504d128a7913a480b1191d66e
mysqldump -u root -p parola>/tmp/wordpressbd.sql
kubectl cp wordpress-mysql-9d89d5879-fcj7m:/tmp/wordpressbd.sql wordpressbd.sql
kubectl cp wordpress-78c9769c97-jmxx5:/var/www/html/ ./wordpresslocal/

trebuie sa cream deploymenturi si pentru php-apache si pentru couchdb
pentru php apache
microk8s kubectl get deploy php-apache -o yaml>myphp-deployment.yaml (obtinem yaml-ul podului)
microk8s kubectl get service php-apache -o yaml>myphp-service.yaml


in Dockerfile-ul dam comanda de copiere a fierului de wordpressbd.sq in docker-entrypoint-initdb.d care va initializa singur baza de date a wordpress-ului nostru
//https://iamvickyav.medium.com/mysql-init-script-on-docker-compose-e53677102e48

adauam toate imaginile noastre in registry-ul privat si modificam yaml-urile astfel incat la pornirea dintr-o comanda sa ia imaginile necesare direct formate de acolo

{"repositories":["couchdb","php-apache","wordpressbd","wordpresslocal"]}

in fisierul de kustomization adaugam toate yaml-urile (care trb sa fie in acelasi director) 

kubectl apply -k .
kubectl delete -k .
curl -X GET http://admin:password@10.0.0.4:5984/chatapp/_all_docs
curl -X GET localhost:32000/v2/_catalog
sudo microk8s kubectl logs wordpress-78c9769c97-jmxx5
alias kubectl='microk8s kubectl'