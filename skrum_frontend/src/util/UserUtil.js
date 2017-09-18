export const RoleLevel = {
  BASIC: 1, // ROLE_LEVEL_NORMAL
  ADMIN: 4, // ROLE_LEVEL_ADMIN
  SUPER: 7, // ROLE_LEVEL_SUPERADMIN
};

export const RoleLevelName = {
  [RoleLevel.BASIC]: '一般',
  [RoleLevel.ADMIN]: '管理者',
  [RoleLevel.SUPER]: 'スーパー管理者',
};

export const isBasicRole = level => level < RoleLevel.ADMIN;
export const isBasicRoleAndAbove = level => level >= RoleLevel.BASIC;
export const isAdminRole = level => level >= RoleLevel.ADMIN && level < RoleLevel.SUPER;
export const isAdminRoleAndAbove = level => level >= RoleLevel.ADMIN;
export const isSuperRole = level => level >= RoleLevel.SUPER;
export const isAuthoritativeOver = (currentLevel, level) => level && level <= currentLevel;
export const isPasswordValid = password => /^(?=.*?[a-zA-Z])(?=.*?[0-9])[a-zA-Z0-9]{8,20}$/.test(password);
