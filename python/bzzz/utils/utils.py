#!/usr/bin/env python

# Copyright (c) 2018 Orange and others.
#
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the Apache License, Version 2.0
# which accompanies this distribution, and is available at
# http://www.apache.org/licenses/LICENSE-2.0
#
#  pylint: disable=import-error

""" Utils """

import sys
import yaml

CONFIG_FILE = sys.path[-1] + "/bzzz/conf/conf.yaml"


def get_parameter_from_yaml(config_file, parameter):
    """
    Returns the value of a given parameter in file.yaml
    parameter must be given in string format with dots
    Example: general.openstack.image_name
    """
    with open(config_file) as my_file:
        file_yaml = yaml.safe_load(my_file)
    my_file.close()
    value = file_yaml

    for element in parameter.split("."):
        value = value.get(element)
        if value is None:
            raise ValueError("Parameter %s not defined" % parameter)
    return value


def get_coefficient(sensor_id):
    """
    Returns coeeficient for HX711 sensor
    """
    coef = (get_parameter_from_yaml(
        CONFIG_FILE,
        "hx.sensor-" + str(sensor_id) + ".etalon") / (
            get_parameter_from_yaml(
                CONFIG_FILE, "hx.sensor-" + str(sensor_id) + ".valeur") -
            get_parameter_from_yaml(
                CONFIG_FILE, "hx.sensor-" + str(sensor_id) + ".tare")))
    return coef
