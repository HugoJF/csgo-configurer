# CSGO-Configurer: automated plugin and extension installation paired with config generation.

## Why
After managing multiple CS:GO servers at once, I found myself updating the same plugin on different servers multiple times, and not only that: changing the same config property on multiple servers, forgetting to update X config on another server, changing something that should not be changed (on different game-modes)... you get the idea, repetitive easy-to-automate tasks.

That being said, I decided to develop this web-panel to automate anything related to SourceMod plugin, extension, and plugin installation with a few ideas in mind:
+ 1 click plugin/extension/config installations.
+ Config files as templates.
+ Anything plugin/extension/config related can be done via the web-panel.
+ Easily change gamemode configs given the same plugin/extension pack.

## Installation
Since this is currently being developed, installation instructions are not yet provided.

## Requirements
Not yet specified

## How everything is wired
In order to keep everything modular and easily to implement, I fixed some basic concepts:

#### Users
Each **user** can have multiple **servers**

#### Servers
Each **server** can have only 1 **installation**

#### Installations
An **installation** is basically a list of **plugins**, each with it's own **config**

#### Plugins
A **plugin** is a folder inside the **plugins** Laravel Disk, containing SourceMod **plugins** template **files** (using Blade Templates)

While this is called a **plugin**, the **files** inside the folder can be anything. After detection, each **file** inside the web-panel will be considered non-renderable (copied to targer server without any changes). A renderable **file** will be rendered by the Blade Compiler with any related configuration keys before being uploaded to the target server.

#### Configs
**Configurations**, or **configs**, are basically a list of keys and values, named **constants** in the database.

A **server** can have multiple configs, **users** can have multiple configs, **plugins** can have multiple configs (but only 1 can actually be used when rendering)

All **config** files related to one **server**, are merged together before rendering, if any duplicate key is detected, the one with highest precedence will be chosen.

Order of precedence:
1. User configs.
2. Server configs.
3. Installation config.

#### Rendering
Is the act of transforming a **plugin** template into a **plugin** that will work on SourceMod by replacing rendering (currently) Blade templates.

#### Syncing
Is the act of uploading rendered **installation** files to the target **server**
