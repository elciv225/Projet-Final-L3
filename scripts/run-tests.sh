#!/bin/bash

echo "Execution des tests..."

if [ -d "./tests" ]; then
  for test in $(find ./tests -type f -name "*.php"); do
    php "$test"
    if [ $? -ne 0 ]; then
      echo "Echec du test: $test"
      exit 1
    fi
  done
  echo "Tous les tests sont passes."
else
  echo "Aucun dossier de tests trouve."
fi

exit 0
php