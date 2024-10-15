USE {$NAMESPACE}_file;
  DELETE FROM file_attachment
  WHERE NOT EXISTS
  (SELECT *
    FROM file
    WHERE phid=file_attachment.filePHID)
