#!/usr/bin/env python

# Copyright (c) 2018 Orange and others.
#
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the Apache License, Version 2.0
# which accompanies this distribution, and is available at
# http://www.apache.org/licenses/LICENSE-2.0
#
# pylint: disable=missing-docstring
import logging
import logging.config
import pkg_resources


class Bzzz():
    """ Bzzz class """
    __logger = logging.getLogger(__name__)

    def function_1(self):
        """
            function 1
        """
        pass

    def function_2(self):
        """
            fonction 2
        """
        pass


def main():
    """Entry point"""
    logging.config.fileConfig(pkg_resources.resource_filename(
        'bzzz', 'bzzz/logging.ini'))
    logging.captureWarnings(True)

if __name__ == "__main__":
    main()
