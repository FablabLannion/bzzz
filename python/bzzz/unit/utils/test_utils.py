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
import sys
import bzzz.utils.utils as bzzz_utils


class UtilsTestingBase(unittest.TestCase):

    """The super class which testing classes could inherit."""

    logging.disable(logging.CRITICAL)
    _config_file = sys.path[-1] + "/bzzz/conf/conf.yaml"

    def setUp(self):
        pass

    def test_get_parameter_from_yaml(self):
        self.assertEqual(60000,
                         bzzz_utils.get_parameter_from_yaml(
                            self._config_file,
                            "general.timeout"))
        self.assertEqual(0.80,
                         bzzz_utils.get_parameter_from_yaml(
                            self._config_file,
                            "temp_sensor.gain_distant"))

    def test_get_config_bad_file(self):
        with self.assertRaises(FileNotFoundError):
            bzzz_utils.get_parameter_from_yaml(
                'mauvais/path',
                'GAIN_distant')

    def test_get_config_param_does_not_exist(self):
        with self.assertRaises(ValueError):
            bzzz_utils.get_parameter_from_yaml(
                self._config_file,
                "Bernard4Ever")


if __name__ == "__main__":
    # logging must be disabled else it calls time.time()
    # what will break these unit tests.
    logging.disable(logging.CRITICAL)
    unittest.main(verbosity=2)
