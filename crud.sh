#!/bin/bash

if [ "$1" == "-h" ] || [ "$1" == "--help" ]
then
  echo "Usage: `basename $0` CamelCaseModelName Caption"
  exit 0
fi

if [ "$#" -ne 2 ]; then
    echo "Illegal number of parameters"
    exit 0
fi

MODEL_NAME=$1
CAPTION=$2
TABLE_NAME=$(echo $MODEL_NAME | sed -r 's/([A-Z])/_\L\1/g' | sed 's/^.//')
VARIABLE_NAME="${MODEL_NAME,}"
SCRIPT_DIR=$(dirname $0)
TEMP_DIR=$(mktemp -d)

echo "Starting..."
echo "Working on temporary folder $TEMP_DIR"
echo "Creating folders..."

mkdir -p $TEMP_DIR/app/Controllers
mkdir -p $TEMP_DIR/app/Models
mkdir -p $TEMP_DIR/app/Policies
mkdir -p $TEMP_DIR/app/Routes
mkdir -p $TEMP_DIR/app/Validators
mkdir -p $TEMP_DIR/resources/views/$TABLE_NAME/

echo "Generating files..."

cp $SCRIPT_DIR/crud/ExampleController.stub $TEMP_DIR/app/Controllers/${MODEL_NAME}Controller.php
cp $SCRIPT_DIR/crud/ExampleModel.stub $TEMP_DIR/app/Models/${MODEL_NAME}.php
cp $SCRIPT_DIR/crud/ExamplePolicy.stub $TEMP_DIR/app/Policies/${MODEL_NAME}Policy.php
cp $SCRIPT_DIR/crud/ExampleRoute.stub $TEMP_DIR/app/Routes/${MODEL_NAME}.php
cp $SCRIPT_DIR/crud/ExampleValidator.stub $TEMP_DIR/app/Validators/${MODEL_NAME}Validator.php
cp -R $SCRIPT_DIR/crud/views/* $TEMP_DIR/resources/views/$TABLE_NAME/

FILES=$(find $TEMP_DIR -type f)

for file in $FILES
do
    if [ -f "$file" ]
    then
        sed -i "s/{MODEL_NAME}/${MODEL_NAME}/g" "$file"
        sed -i "s/{TABLE_NAME}/${TABLE_NAME}/g" "$file"
        sed -i "s/{VARIABLE_NAME}/${VARIABLE_NAME}/g" "$file"
        sed -i "s/{CAPTION}/${CAPTION}/g" "$file"

        echo "$file generated!"
    fi
done

echo "Copying files"
cp -Rnv $TEMP_DIR/* ./

echo "Deleting temporary folder"
rm -rf $TEMP_DIR

echo "Finished!"
