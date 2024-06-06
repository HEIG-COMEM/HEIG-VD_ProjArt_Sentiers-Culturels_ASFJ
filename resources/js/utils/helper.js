const back = () => window.history.back();
const getImgPath = (path) => `/storage/pictures/${path}`;
const getTagsName = (tags) => tags.map((tag) => tag.name);

export { back, getImgPath, getTagsName };