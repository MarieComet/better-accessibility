# Better Accessibility

## Description 

This WordPress plugin adds settings to improve accessibility and automatically corrects accessibility issues.
It gathers several small improvements implemented during my projects. It might not fit you 100%, it has no settings pages.

## What it does

### Menus
- Removes the "Title attribute" field from menu items
- Automatically adds an aria-label containing "_menu title_ **(new tab)**" attribute to links that open in a new tab (target blank)
- Replace empty link (href="#") by span
- Adds an aria-label field to menu items (which is automatically filled with "_menu aria label_ **(new tab)**" if the link opens in a new tab).

For example, a menu item linked to a Twitter account, which open in a new tab, with "Twitter" as navigation label, with "Visit the twitter profile of Marie Comet" as Aria label :

![image](https://user-images.githubusercontent.com/7976501/154801670-ce18b867-2fdf-4a2c-928e-b282a6435905.png)

Will be turned into : `<a target="_blank" rel="noopener" href="https://twitter.com/CometMarie" aria-label="Visit the twitter profile of Marie Comet (new tab)">Twitter</a>`

### Editor

#### Button block
- Automatically adds an aria-label containing "_button text_ **(new tab)**" attribute to links that open in a new tab (target blank)
- Adds an aria-label setting to Block Toolbar (which is automatically filled with "_button aria label_ **(new tab)**" if the link opens in a new tab)

![image](https://user-images.githubusercontent.com/7976501/154801883-8ed5efa0-0534-4b2f-9e5f-81a9d71885e3.png)




Available in French and English.
