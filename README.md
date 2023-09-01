# DnD 5e XP Tracker
This is just a personal project to test how to apply some Domain Driven Design and Clean Architectures to a Vue (or any Typescript) framework.

The current scope tries to manage how many Characters are in a current party and their basic stats (level and hit points).

With a party defined you will be able to add your planned encounters. Each encounter should have at least one monster. When you add one monster to an encounter it will calculate the level (Easy, Medium, Hard or Deadly) of the encounter as explained on DMG (pag xxx).

When your party defeats or overcomes the encounter you can easily add the XP to the party members, to track their progress.

As there is no backend to store data, an import/export option is added to be able to save your encounter and party data so you can use it on multiple devices.
