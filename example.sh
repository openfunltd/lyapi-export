term=11
year=2025

#no argument needed
php dump-committee.php

#default term=11(hardcode)
php dump-legislator.php $term

#default term=11(hardcoded)
php dump-bill.php $term

#default term=11(hardcoded)
php dump-meet.php $term

#default term=11(hardcode)
php dump-interpellation.php $term

#default year is now year
php dump-ivod.php $year

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
