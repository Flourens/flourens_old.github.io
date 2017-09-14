#!/bin/bash

closure-compiler --js_output_file /tmp/cloud_api.js ./cloudapidemo.js raphael-min.js SimpleAjaxUploader.min.js
cp ./cloudapidemo.css /tmp/cloud_api.css

echo "Cloud JavaScript written to /tmp/cloud_api.js and /tmp/cloud_api.css"
