#!/bin/bash

REMOTE="$1"

if [ "$REMOTE" == "gitlab" ]; then
  echo "Remote actif : GitLab -> .gitignore.prod"
  cp .gitignore.prod .gitignore
elif [ "$REMOTE" == "github" ]; then
  echo "Remote actif : GitHub -> .gitignore.dev"
  cp .gitignore.dev .gitignore
else
  echo "Remote inconnu ($REMOTE), fallback sur .gitignore.dev"
  cp .gitignore.dev .gitignore
fi
