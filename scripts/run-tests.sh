#!/bin/bash

echo "🧪 Exécution des tests PHP..."

if [ -d "./tests" ]; then
  for test in $(find ./tests -type f -name "*.php"); do
    php "$test"
    if [ $? -ne 0 ]; then
      echo "❌ Échec du test: $test"
      exit 1
    fi
  done
  echo "✅ Tous les tests sont passés."
else
  echo "⚠️ Aucun dossier de tests trouvé."
fi

exit 0
