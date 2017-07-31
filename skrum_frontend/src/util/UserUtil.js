export const RoleLevel = {
  BASIC: 1, // ROLE_LEVEL_NORMAL
  ADMIN: 4, // ROLE_LEVEL_ADMIN
  SUPER: 7, // ROLE_LEVEL_SUPERADMIN
};

export const isBasicRole = level => level < RoleLevel.ADMIN;
export const isAdminRole = level => level >= RoleLevel.ADMIN;
export const isSuperRole = level => level >= RoleLevel.SUPER;
