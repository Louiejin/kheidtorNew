migrate-refresh:
	ssh ubuntu@gitlab.acctechnology.ph '\
		cd kheditor; pwd; \
		git pull; \
		mysql -uroot -proot < database/init.sql; \
		mysql -uroot -proot < database/khengine.sql; \
		php artisan migrate:refresh \
		'
migrate:
	ssh ubuntu@gitlab.acctechnology.ph '\
		cd kheditor; pwd; \
		git pull; \
		php artisan migrate \
		'


deploy:
	ssh ubuntu@gitlab.acctechnology.ph '\
		cd kheditor; pwd; \
		git pull; \
		sudo /etc/init.d/supervisor restart \
		'
