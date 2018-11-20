#!/usr/bin/env python

# Copyright (c) 2017 Orange and others.
#
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the Apache License, Version 2.0
# which accompanies this distribution, and is available at
# http://www.apache.org/licenses/LICENSE-2.0

# pylint: disable=missing-docstring

import logging
import unittest


class Hx711TestingBase(unittest.TestCase):

    """The super class which testing classes could inherit."""

    logging.disable(logging.CRITICAL)

    def setUp(self):
        pass

    def test_init_sensor(self):
        pass

    def test_calibrate(self):
        pass

    def test_get_nb_sensors(self):
        pass

    def test_get_value_from_sensor_X(self):
        pass

    def test_calculate_sensor_value(self):
        pass

    def test_power_on(self):
        pass

    def test_power_off(self):
        pass


if __name__ == "__main__":
    # logging must be disabled else it calls time.time()
    # what will break these unit tests.
    logging.disable(logging.CRITICAL)
    unittest.main(verbosity=2)