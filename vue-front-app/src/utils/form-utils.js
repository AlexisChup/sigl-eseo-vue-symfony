/**
 * @param
 *  obj: the object that contains only string values
 *
 * @returns
 *  the obj that was passed in the params with "" for all his properties
 */
export const resetValuesFormStringObject = (obj) => {
  const allProperties = Object.getOwnPropertyNames(obj);

  allProperties.forEach((property) => {
    if (property !== "id") {
      obj[property] = "";
    }
  });
};

/**
 * @param
 *  form: the object that contains only string values
 *
 * @returns
 *  true if all his property is not null, false otherwise
 */
export const isFormStringValid = (form) => {
  let isFormValidate = true;
  const allProperties = Object.getOwnPropertyNames(form);
  allProperties.forEach((property) => {
    if (
      property !== "id" &&
      property !== "adresse" &&
      property !== "motDePasse" &&
      property !== "actif"
    ) {
      isFormValidate &= form[property].length > 0;
    }
  });

  return isFormValidate;
};

/**
 * @param
 *  form: the object that contains only string values
 *
 * @returns
 *  true if all his property is null, false otherwise
 */
export const isFormStringEmpty = (form) => {
  let isFormEmpty = true;
  const allProperties = Object.getOwnPropertyNames(form);

  allProperties.forEach((property) => {
    if (property !== "id") {
      isFormEmpty &= form[property].length == 0;
    }
  });

  return isFormEmpty;
};
