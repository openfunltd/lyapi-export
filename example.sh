term=11
year=2025

#default term=11(hardcode)
php dump-legislator.php $term

#default term=11(hardcoded)
php dump-bill.php $term

#default term=11(hardcoded)
php dump-meet.php $term

#no argument needed
php dump-law.php

#default year is now year
php dump-law_version.php $year

#default year is now year
php dump-law_content.php $year

#no argument needed
php dump-gazette.php

#default term=11(hardcoded)
php dump-gazette_agenda.php $term
