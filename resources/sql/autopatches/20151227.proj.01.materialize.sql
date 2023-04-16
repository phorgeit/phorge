/* PhorgeProjectProjectHasMemberEdgeType::EDGECONST = 13 */
/* PhorgeProjectMaterializedMemberEdgeType::EDGECONST = 60 */

INSERT IGNORE INTO {$NAMESPACE}_project.edge (src, type, dst, dateCreated)
  SELECT src, 60, dst, dateCreated FROM {$NAMESPACE}_project.edge
  WHERE type = 13;
